<?php

namespace App\Exports;

use App\Models\Service;
use App\Services\Search\ServiceSearch;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ServiceExport extends BaseExport implements WithColumnFormatting
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Price',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param Service $service
     * @return array
     */
    public function map($service): array
    {
        return [
            $service->id,
            $service->name,
            $service->price,
            $service->created_at->format('Y-m-d H:i:s'),
            $service->updated_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Services';
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_DATE_DATETIME,
            'E' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
