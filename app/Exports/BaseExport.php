<?php

namespace App\Exports;

use App\Services\Search\Base\BaseSearch;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    /**
     * @var BaseSearch
     */
    protected $searchService;

    /**
     * BaseExport constructor.
     *
     * @param BaseSearch $searchService
     */
    public function __construct(BaseSearch $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Query to retrieve data for export
     *
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->searchService->getQuery();
    }

    /**
     * Define the headings for the export
     *
     * @return array
     */
    abstract public function headings(): array;

    /**
     * Map the data for export
     *
     * @param mixed $row
     * @return array
     */
    abstract public function map($row): array;

    /**
     * Get the worksheet title
     *
     * @return string
     */
    abstract public function title(): string;

    /**
     * Apply styles to the export
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
