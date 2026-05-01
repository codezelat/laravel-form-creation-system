<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use App\Models\FormSubmission;
use App\Services\TurnstileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FormController extends Controller
{
    protected TurnstileService $turnstileService;

    public function __construct(TurnstileService $turnstileService)
    {
        $this->turnstileService = $turnstileService;
    }
    /**
     * Store or update a form (auto-save)
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:forms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'fields' => 'required|array',
            'fields.*.type' => 'required|string',
            'fields.*.label' => 'required|string',
            'fields.*.required' => 'boolean',
            'fields.*.options' => 'nullable|array',
            'fields.*.fileTypes' => 'nullable|string',
            'fields.*.fileSettings' => 'nullable|array',
            'fields.*.fileSettings.allowedTypes' => 'nullable|array',
            'fields.*.fileSettings.allowedTypes.*' => 'nullable|string',
            'fields.*.fileSettings.maxSize' => 'nullable|numeric',
            'fields.*.maxFileSize' => 'nullable|numeric',
        ]);

        $color = $this->normalizeFormColor($request->color);

        // Create or update form
        if ($request->id) {
            $form = Form::findOrFail($request->id);
            $form->update([
                'title' => $request->title,
                'description' => $request->description,
                'color' => $color,
            ]);
            
            // Delete existing fields
            $form->fields()->delete();
        } else {
            $form = Form::create([
                'title' => $request->title,
                'description' => $request->description,
                'color' => $color,
                'status' => 'draft',
            ]);
        }

        // Create fields
        foreach ($request->fields as $index => $fieldData) {
            $fileSettings = null;
            if ($fieldData['type'] === 'file') {
                $acceptedTypes = $fieldData['fileTypes']
                    ?? $fieldData['fileSettings']['accepted_types']
                    ?? (isset($fieldData['fileSettings']['allowedTypes'])
                        ? implode(', ', array_filter($fieldData['fileSettings']['allowedTypes']))
                        : null);

                $fileSettings = [
                    'accepted_types' => $acceptedTypes ?: 'pdf, doc, docx, jpg, png',
                    'max_size' => $fieldData['maxFileSize']
                        ?? $fieldData['fileSettings']['max_size']
                        ?? $fieldData['fileSettings']['maxSize']
                        ?? 5,
                ];
            }

            FormField::create([
                'form_id' => $form->id,
                'type' => $fieldData['type'],
                'label' => $fieldData['label'],
                'required' => $fieldData['required'] ?? false,
                'options' => $fieldData['options'] ?? null,
                'file_settings' => $fileSettings,
                'order' => $index,
            ]);
        }

        return response()->json([
            'success' => true,
            'form_id' => $form->id,
            'message' => 'Form saved successfully',
        ]);
    }

    /**
     * Publish a form
     */
    public function publish(Request $request, $id)
    {
        $request->validate([
            'slug' => 'nullable|string|max:255|unique:forms,slug,' . $id,
            'form_status' => 'required|in:active,inactive',
        ]);

        $form = Form::findOrFail($id);
        
        // Generate slug if custom one is not provided
        $slug = $request->slug ?? Str::slug($form->title) . '-' . Str::random(8);
        
        $form->update([
            'status' => 'published',
            'slug' => $slug,
            'form_status' => $request->form_status,
        ]);

        return response()->json([
            'success' => true,
            'slug' => $form->slug,
            'url' => route('form.show', $form->slug),
        ]);
    }

    /**
     * Unpublish a form
     */
    public function unpublish($id)
    {
        $form = Form::findOrFail($id);
        $form->update(['status' => 'draft']);

        return response()->json([
            'success' => true,
            'message' => 'Form unpublished successfully',
        ]);
    }

    /**
     * Get form data for editing
     */
    public function getFormData($id)
    {
        $form = Form::with('fields')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'form' => $form
        ]);
    }

    /**
     * Show public form
     */
    public function show($slug)
    {
        $form = Form::where('slug', $slug)
            ->where('status', 'published')
            ->with('fields')
            ->firstOrFail();

        // Check if form is accessible (not inactive unless admin)
        if ($form->form_status === 'inactive' && !session('admin_authenticated')) {
            return view('form.locked');
        }

        $turnstileSiteKey = $this->turnstileService->getSiteKey();

        return view('form.show', compact('form', 'turnstileSiteKey'));
    }

    /**
     * Submit a form
     */
    public function submit(Request $request, $slug)
    {
        $form = Form::where('slug', $slug)
            ->where('status', 'published')
            ->with('fields')
            ->firstOrFail();

        // Check if form accepts submissions
        if ($form->form_status === 'inactive' && !session('admin_authenticated')) {
            return response()->json([
                'success' => false,
                'message' => 'This form is not accepting submissions.',
            ], 403);
        }

        // Validate Turnstile token
        $turnstileToken = $request->input('cf-turnstile-response');
        if (!$turnstileToken) {
            return response()->json([
                'success' => false,
                'message' => 'Please complete the security verification.',
            ], 422);
        }

        if (!$this->turnstileService->verify($turnstileToken, $request->ip())) {
            return response()->json([
                'success' => false,
                'message' => 'Security verification failed. Please try again.',
            ], 422);
        }

        $submissionData = [];
        $uploadedFiles = [];
        $pendingFiles = [];

        // Process each field
        foreach ($form->fields as $field) {
            $fieldKey = 'field_' . $field->id;
            $fieldValue = $request->input($fieldKey);

            // Validate required fields
            if ($field->required && empty($fieldValue) && $field->type !== 'file') {
                return response()->json([
                    'success' => false,
                    'message' => "The field '{$field->label}' is required.",
                ], 422);
            }

            if ($field->required && $field->type === 'file' && !$request->hasFile($fieldKey)) {
                return response()->json([
                    'success' => false,
                    'message' => "The field '{$field->label}' is required.",
                ], 422);
            }

            // Handle file uploads
            if ($field->type === 'file' && $request->hasFile($fieldKey)) {
                $file = $request->file($fieldKey);
                
                // Validate file
                $fileSettings = $field->file_settings;
                if ($fileSettings) {
                    $maxSize = ($fileSettings['max_size'] ?? 5) * 1024; // Convert to KB
                    if ($file->getSize() / 1024 > $maxSize) {
                        return response()->json([
                            'success' => false,
                            'message' => "File size exceeds maximum allowed size of {$fileSettings['max_size']}MB for '{$field->label}'.",
                        ], 422);
                    }

                    if (!$this->isAllowedUploadType($file, $fileSettings['accepted_types'] ?? null)) {
                        return response()->json([
                            'success' => false,
                            'message' => "File type is not allowed for '{$field->label}'.",
                        ], 422);
                    }
                }

                $pendingFiles[] = [
                    'field_key' => $fieldKey,
                    'file' => $file,
                ];
            } else {
                $submissionData[$fieldKey] = $fieldValue;
            }
        }

        DB::transaction(function () use ($form, $request, &$submissionData, &$uploadedFiles, $pendingFiles) {
            $submission = FormSubmission::create([
                'form_id' => $form->id,
                'submission_data' => $submissionData,
                'field_snapshot' => FormSubmission::createFieldSnapshot($form),
                'files' => $uploadedFiles,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'submitted_at' => now(),
            ]);

            foreach ($pendingFiles as $pendingFile) {
                $file = $pendingFile['file'];
                $fieldKey = $pendingFile['field_key'];

                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs(
                    "submissions/{$form->id}/{$submission->id}",
                    $fileName,
                    'public'
                );

                $uploadedFiles[$fieldKey] = $path;
                $submissionData[$fieldKey] = $fileName;
            }

            if (!empty($pendingFiles)) {
                $submission->update([
                    'submission_data' => $submissionData,
                    'files' => $uploadedFiles,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Form submitted successfully!',
        ]);
    }

    /**
     * Delete a form
     */
    public function destroy($id)
    {
        $form = Form::findOrFail($id);
        
        // Delete associated files
        $submissions = $form->submissions;
        foreach ($submissions as $submission) {
            if ($submission->files) {
                foreach ($submission->files as $filePath) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        }
        
        $form->delete();

        return response()->json([
            'success' => true,
            'message' => 'Form deleted successfully',
        ]);
    }

    protected function isAllowedUploadType($file, ?string $acceptedTypes): bool
    {
        if (!$acceptedTypes) {
            return true;
        }

        $allowedExtensions = collect(explode(',', $acceptedTypes))
            ->map(fn ($type) => strtolower(trim($type)))
            ->map(fn ($type) => ltrim($type, '.'))
            ->filter(fn ($type) => $type !== '' && !str_contains($type, '/'))
            ->values();

        if ($allowedExtensions->isEmpty()) {
            return true;
        }

        return $allowedExtensions->contains(strtolower($file->getClientOriginalExtension()));
    }

    protected function normalizeFormColor(?string $color): string
    {
        $color = strtolower(trim((string) $color));
        $allowedColors = ['blue', 'green', 'purple', 'red', 'yellow', 'indigo'];

        return in_array($color, $allowedColors, true) ? $color : 'blue';
    }
}
