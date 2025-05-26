<?php

namespace App\Exports;

use App\Models\Patient;
use App\Services\Search\PatientSearch;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PatientExport extends BaseExport implements WithColumnFormatting
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Age',
            'Gender',
            'Phone',
            'Mobile',
            'File Number',
            'Total Amount',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param Patient $patient
     * @return array
     */
    public function map($patient): array
    {
        return [
            $patient->id,
            $patient->name,
            $patient->age,
            $patient->gender,
            $patient->phone,
            $patient->mobile,
            $patient->file_number,
            $patient->total_amount,
            $patient->created_at->format('Y-m-d H:i:s'),
            $patient->updated_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Patients';
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_DATE_DATETIME,
            'J' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
