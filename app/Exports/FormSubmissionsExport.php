<?php

namespace App\Exports;

use App\Models\Form;
use App\Models\FormSubmission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;

class FormSubmissionsExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithTitle, 
    ShouldAutoSize,
    WithEvents
{
    protected $form;
    protected $fields;
    protected $headings;
    protected $fileFieldColumns = []; // Track which columns have file uploads

    public function __construct(Form $form)
    {
        $this->form = $form;
        $this->fields = $form->fields()->orderBy('order')->get();
        $this->prepareHeadings();
    }

    /**
     * Prepare column headings
     */
    protected function prepareHeadings()
    {
        $this->headings = [
            'Submission ID',
            'Submitted Date',
            'Submitted Time',
        ];

        // Add field labels as column headings and track file columns
        $columnIndex = 3; // Starting after ID, Date, Time (0-indexed: 3 = column D)
        foreach ($this->fields as $field) {
            $this->headings[] = $field->label . ($field->required ? ' *' : '');
            
            // Track file upload columns
            if ($field->type === 'file') {
                $this->fileFieldColumns[] = $columnIndex;
            }
            $columnIndex++;
        }

        $this->headings[] = 'IP Address';
        $this->headings[] = 'User Agent';
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return FormSubmission::where('form_id', $this->form->id)
            ->orderBy('submitted_at', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * Map each submission to a row
     */
    public function map($submission): array
    {
        $submissionData = is_string($submission->submission_data) 
            ? json_decode($submission->submission_data, true) 
            : $submission->submission_data;

        $row = [
            '#' . $submission->id,
            $submission->submitted_at->format('M d, Y'),
            $submission->submitted_at->format('H:i:s'),
        ];

        // Map field values in the correct order
        foreach ($this->fields as $field) {
            $fieldKey = 'field_' . $field->id;
            $value = $submissionData[$fieldKey] ?? '';

            // Format the value based on field type
            $row[] = $this->formatFieldValue($value, $field, $submission);
        }

        // Add metadata
        $row[] = $submission->ip_address ?? 'N/A';
        $row[] = $submission->user_agent ?? 'N/A';

        return $row;
    }

    /**
     * Format field value based on field type
     */
    protected function formatFieldValue($value, $field, $submission)
    {
        if (empty($value) && $value !== '0' && $value !== 0) {
            return 'N/A';
        }

        switch ($field->type) {
            case 'checkbox':
                // Handle checkbox arrays
                if (is_array($value)) {
                    return implode(', ', $value);
                }
                return $value;

            case 'file':
                // Handle file uploads - return full URL
                $files = is_string($submission->files) 
                    ? json_decode($submission->files, true) 
                    : $submission->files;
                
                $fieldKey = 'field_' . $field->id;
                if (isset($files[$fieldKey])) {
                    // Return full URL to the file
                    return url('storage/' . $files[$fieldKey]);
                }
                return $value;

            case 'date':
                // Format dates nicely
                try {
                    return \Carbon\Carbon::parse($value)->format('M d, Y');
                } catch (\Exception $e) {
                    return $value;
                }

            case 'email':
                return strtolower($value);

            case 'number':
                // Format numbers
                return is_numeric($value) ? number_format($value, 2) : $value;

            case 'select':
            case 'radio':
            case 'text':
            case 'textarea':
            default:
                return $value;
        }
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        $lastColumn = chr(65 + count($this->headings) - 1); // Convert to Excel column letter
        $totalRows = $this->collection()->count() + 1; // +1 for header

        // Style header row
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'], // Blue-600
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Set header row height
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Style data rows with alternating colors
        for ($i = 2; $i <= $totalRows; $i++) {
            $fillColor = ($i % 2 == 0) ? 'F3F4F6' : 'FFFFFF'; // Gray-100 : White
            
            $sheet->getStyle('A' . $i . ':' . $lastColumn . $i)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $fillColor],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB'], // Gray-200
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ]);
        }

        // Freeze the header row
        $sheet->freezePane('A2');

        return [];
    }

    /**
     * Set the worksheet title
     */
    public function title(): string
    {
        // Excel sheet names can't be longer than 31 characters
        $title = substr($this->form->title, 0, 31);
        // Remove invalid characters
        $title = preg_replace('/[\\\\\/\*\?\[\]]/', '', $title);
        return $title ?: 'Submissions';
    }

    /**
     * Register events to make file URLs clickable
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $totalRows = $this->collection()->count() + 1; // +1 for header
                
                // Make file upload URLs clickable hyperlinks
                foreach ($this->fileFieldColumns as $colIndex) {
                    $columnLetter = $this->getColumnLetter($colIndex);
                    
                    // Loop through all data rows (skip header)
                    for ($row = 2; $row <= $totalRows; $row++) {
                        $cell = $sheet->getCell($columnLetter . $row);
                        $url = $cell->getValue();
                        
                        // Only process if it looks like a URL
                        if ($url && (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0)) {
                            // Extract filename from URL for display
                            $filename = basename(parse_url($url, PHP_URL_PATH));
                            
                            // Set the hyperlink
                            $cell->getHyperlink()->setUrl($url);
                            $cell->setValue($filename); // Show filename but link to URL
                            
                            // Style the link (blue, underlined)
                            $sheet->getStyle($columnLetter . $row)->applyFromArray([
                                'font' => [
                                    'color' => ['rgb' => '0563C1'], // Blue color
                                    'underline' => true,
                                ],
                            ]);
                        }
                    }
                }
            },
        ];
    }

    /**
     * Convert column index to Excel column letter (0=A, 1=B, etc.)
     */
    protected function getColumnLetter($index): string
    {
        return chr(65 + $index);
    }
}
