<?php

namespace App\Exports;

use App\Models\Appointment;
use App\Services\Search\AppointmentSearch;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AppointmentExport extends BaseExport implements WithColumnFormatting
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Patient Name',
            'Date',
            'Notes',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param Appointment $appointment
     * @return array
     */
    public function map($appointment): array
    {
        return [
            $appointment->id,
            $appointment->patient->name ?? 'N/A',
            $appointment->date?->format('Y-m-d H:i:s'),
            $appointment->notes,
            $appointment->created_at->format('Y-m-d H:i:s'),
            $appointment->updated_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Appointments';
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_DATE_DATETIME,
            'E' => NumberFormat::FORMAT_DATE_DATETIME,
            'F' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
