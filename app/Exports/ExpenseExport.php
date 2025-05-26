<?php

namespace App\Exports;

use App\Models\Expense;
use App\Services\Search\ExpenseSearch;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExpenseExport extends BaseExport implements WithColumnFormatting
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Description',
            'Amount',
            'Date',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param Expense $expense
     * @return array
     */
    public function map($expense): array
    {
        return [
            $expense->id,
            $expense->title,
            $expense->description,
            $expense->amount,
            $expense->date?->format('Y-m-d'),
            $expense->created_at->format('Y-m-d H:i:s'),
            $expense->updated_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Expenses';
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_DATE_DATETIME,
            'G' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
