<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FormController extends Controller
{
    /**
     * Store or update a form (auto-save)
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:forms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string',
            'fields' => 'required|array',
            'fields.*.type' => 'required|string',
            'fields.*.label' => 'required|string',
            'fields.*.required' => 'boolean',
            'fields.*.options' => 'nullable|array',
            'fields.*.fileTypes' => 'nullable|string',
            'fields.*.maxFileSize' => 'nullable|numeric',
        ]);

        // Create or update form
        if ($request->id) {
            $form = Form::findOrFail($request->id);
            $form->update([
                'title' => $request->title,
                'description' => $request->description,
                'color' => $request->color,
            ]);
            
            // Delete existing fields
            $form->fields()->delete();
        } else {
            $form = Form::create([
                'title' => $request->title,
                'description' => $request->description,
                'color' => $request->color,
                'status' => 'draft',
            ]);
        }

        // Create fields
        foreach ($request->fields as $index => $fieldData) {
            $fileSettings = null;
            if ($fieldData['type'] === 'file' && isset($fieldData['fileTypes'])) {
                $fileSettings = [
                    'accepted_types' => $fieldData['fileTypes'],
                    'max_size' => $fieldData['maxFileSize'] ?? 5,
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
     * Show public form
     */
    public function show($slug)
    {
        $form = Form::where('slug', $slug)
            ->where('status', 'published')
            ->with('fields')
            ->firstOrFail();

                // Check if form is accessible (not inactive unless admin)
        if ($form->form_status === 'inactive' && !session()->has('admin_logged_in')) {
            return view('form.locked');
        }

        return view('form.show', compact('form'));
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

        $submissionData = [];
        $uploadedFiles = [];

        // Process each field
        foreach ($form->fields as $field) {
            $fieldValue = $request->input('field_' . $field->id);

            // Validate required fields
            if ($field->required && empty($fieldValue) && $field->type !== 'file') {
                return response()->json([
                    'success' => false,
                    'message' => "The field '{$field->label}' is required.",
                ], 422);
            }

            // Handle file uploads
            if ($field->type === 'file' && $request->hasFile('field_' . $field->id)) {
                $file = $request->file('field_' . $field->id);
                
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
                }

                // Create submission first to get ID
                $submission = FormSubmission::create([
                    'form_id' => $form->id,
                    'submission_data' => [],
                    'files' => [],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'submitted_at' => now(),
                ]);

                // Store file
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs(
                    "submissions/{$form->id}/{$submission->id}",
                    $fileName,
                    'public'
                );

                $uploadedFiles['field_' . $field->id] = $path;
                $submissionData['field_' . $field->id] = $fileName;
            } else {
                $submissionData['field_' . $field->id] = $fieldValue;
            }
        }

        // Update or create submission
        if (!isset($submission)) {
            $submission = FormSubmission::create([
                'form_id' => $form->id,
                'submission_data' => $submissionData,
                'files' => $uploadedFiles,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'submitted_at' => now(),
            ]);
        } else {
            $submission->update([
                'submission_data' => $submissionData,
                'files' => $uploadedFiles,
            ]);
        }

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
}
