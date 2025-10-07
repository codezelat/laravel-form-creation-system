<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmission extends Model
{
    protected $fillable = [
        'form_id',
        'submission_data',
        'field_snapshot',
        'files',
        'ip_address',
        'user_agent',
        'submitted_at'
    ];

    protected $casts = [
        'submission_data' => 'array',
        'field_snapshot' => 'array',
        'files' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get submission value for a specific form field with robust backwards compatibility
     * 
     * @param FormField $field
     * @return mixed
     */
    public function getFieldValue($field)
    {
        $submissionData = is_string($this->submission_data) 
            ? json_decode($this->submission_data, true) 
            : $this->submission_data;

        if (empty($submissionData)) {
            return null;
        }

        // 1. Try current field ID first (for new submissions)
        $currentFieldKey = 'field_' . $field->id;
        if (isset($submissionData[$currentFieldKey])) {
            return $submissionData[$currentFieldKey];
        }

        // 2. Use field snapshot if available (best approach for historical data)
        if (!empty($this->field_snapshot)) {
            return $this->getValueFromSnapshot($field, $submissionData);
        }

        // 3. Fallback: Intelligent matching for old submissions without snapshots
        return $this->findValueByIntelligentMatching($field, $submissionData);
    }

    /**
     * Get value using field snapshot (most reliable method)
     */
    private function getValueFromSnapshot($currentField, $submissionData)
    {
        $snapshot = $this->field_snapshot;
        
        // Find the historical field that best matches the current field
        $bestMatch = null;
        $bestScore = 0;
        
        foreach ($snapshot as $historicalField) {
            $score = $this->calculateFieldMatchScore($currentField, $historicalField);
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $historicalField;
            }
        }
        
        if ($bestMatch && $bestScore > 0.5) { // Confidence threshold
            $historicalFieldKey = 'field_' . $bestMatch['id'];
            return $submissionData[$historicalFieldKey] ?? null;
        }
        
        return null;
    }

    /**
     * Calculate similarity score between current field and historical field
     */
    private function calculateFieldMatchScore($currentField, $historicalField)
    {
        $score = 0;
        
        // Exact label match (highest priority)
        if (strtolower(trim($currentField->label)) === strtolower(trim($historicalField['label']))) {
            $score += 0.6;
        } else {
            // Partial label match using similar_text
            $labelSimilarity = 0;
            similar_text(
                strtolower(trim($currentField->label)), 
                strtolower(trim($historicalField['label'])), 
                $labelSimilarity
            );
            $score += ($labelSimilarity / 100) * 0.4; // Max 0.4 for partial match
        }
        
        // Same field type
        if ($currentField->type === $historicalField['type']) {
            $score += 0.2;
        }
        
        // Same position in form
        if ($currentField->order === $historicalField['order']) {
            $score += 0.1;
        }
        
        // Same required status
        if ($currentField->required === ($historicalField['required'] ?? false)) {
            $score += 0.05;
        }
        
        // For select/radio fields, check if options are similar
        if (in_array($currentField->type, ['select', 'radio', 'checkbox']) && 
            isset($historicalField['options'])) {
            $currentOptions = $currentField->options ?? [];
            $historicalOptions = $historicalField['options'] ?? [];
            
            if (!empty($currentOptions) && !empty($historicalOptions)) {
                $commonOptions = array_intersect($currentOptions, $historicalOptions);
                $optionSimilarity = count($commonOptions) / max(count($currentOptions), count($historicalOptions));
                $score += $optionSimilarity * 0.05;
            }
        }
        
        return $score;
    }

    /**
     * Fallback method for submissions without field snapshots
     */
    private function findValueByIntelligentMatching($field, $submissionData)
    {
        // Get all field keys from submission
        $submissionFieldKeys = array_filter(array_keys($submissionData), function($key) {
            return str_starts_with($key, 'field_');
        });
        
        if (empty($submissionFieldKeys)) {
            return null;
        }
        
        // Try to match by position (simplest fallback)
        $currentFields = $this->form->fields()->orderBy('order')->get();
        $currentFieldPosition = null;
        
        foreach ($currentFields as $index => $currentField) {
            if ($currentField->id === $field->id) {
                $currentFieldPosition = $index;
                break;
            }
        }
        
        if ($currentFieldPosition !== null) {
            // Sort submission keys to maintain order
            sort($submissionFieldKeys);
            
            if (isset($submissionFieldKeys[$currentFieldPosition])) {
                $matchedKey = $submissionFieldKeys[$currentFieldPosition];
                return $submissionData[$matchedKey];
            }
        }
        
        return null;
    }

    /**
     * Get file value for a specific form field with robust backwards compatibility
     * 
     * @param FormField $field
     * @return mixed
     */
    public function getFieldFile($field)
    {
        $files = is_string($this->files) 
            ? json_decode($this->files, true) 
            : ($this->files ?? []);

        if (empty($files)) {
            return null;
        }

        // 1. Try current field ID first
        $currentFieldKey = 'field_' . $field->id;
        if (isset($files[$currentFieldKey])) {
            return $files[$currentFieldKey];
        }

        // 2. Use field snapshot for intelligent matching
        if (!empty($this->field_snapshot)) {
            $snapshot = $this->field_snapshot;
            
            foreach ($snapshot as $historicalField) {
                if ($historicalField['type'] === 'file') {
                    $score = $this->calculateFieldMatchScore($field, $historicalField);
                    if ($score > 0.5) {
                        $historicalFieldKey = 'field_' . $historicalField['id'];
                        if (isset($files[$historicalFieldKey])) {
                            return $files[$historicalFieldKey];
                        }
                    }
                }
            }
        }

        // 3. Fallback: position-based matching for file fields
        $currentFileFields = $this->form->fields()->where('type', 'file')->orderBy('order')->get();
        $fileKeys = array_keys($files);
        
        $currentFieldIndex = $currentFileFields->search(function($f) use ($field) {
            return $f->id === $field->id;
        });
        
        if ($currentFieldIndex !== false && isset($fileKeys[$currentFieldIndex])) {
            $matchedKey = $fileKeys[$currentFieldIndex];
            return $files[$matchedKey] ?? null;
        }

        return null;
    }

    /**
     * Create field snapshot from current form fields
     * This should be called when a submission is created
     */
    public static function createFieldSnapshot($form)
    {
        return $form->fields()->orderBy('order')->get()->map(function($field) {
            return [
                'id' => $field->id,
                'type' => $field->type,
                'label' => $field->label,
                'order' => $field->order,
                'required' => $field->required,
                'options' => $field->options,
            ];
        })->toArray();
    }
}
