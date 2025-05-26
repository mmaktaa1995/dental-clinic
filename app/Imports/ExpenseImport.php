<?php

namespace App\Imports;

use App\Models\Expense;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExpenseImport extends BaseImport
{
    /**
     * Process a single row
     *
     * @param Collection $row
     * @return void
     */
    protected function processRow(Collection $row)
    {
        $expense = new Expense([
            'title' => $row['title'],
            'description' => $row['description'] ?? null,
            'amount' => $row['amount'],
            'date' => Carbon::parse($row['date']),
            'user_id' => Auth::id(),
        ]);

        $expense->save();

        return $expense;
    }

    /**
     * Get validation rules for the import
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
        ];
    }

    /**
     * Get custom validation messages
     *
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            'title.required' => 'The expense title is required.',
            'amount.required' => 'The expense amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.',
            'date.required' => 'The expense date is required.',
            'date.date' => 'The expense date must be a valid date.',
        ];
    }

    /**
     * Get custom validation attributes
     *
     * @return array
     */
    public function customValidationAttributes(): array
    {
        return [
            'title' => 'expense title',
            'description' => 'expense description',
            'amount' => 'expense amount',
            'date' => 'expense date',
        ];
    }
}
