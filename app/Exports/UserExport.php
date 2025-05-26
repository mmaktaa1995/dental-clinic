<?php

namespace App\Exports;

use App\Models\User;
use App\Services\Search\UserSearch;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UserExport extends BaseExport implements WithColumnFormatting
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param User $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->created_at->format('Y-m-d H:i:s'),
            $user->updated_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Users';
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_DATE_DATETIME,
            'E' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
