<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Validators\Failure;

abstract class BaseImport implements 
    ToCollection, 
    WithHeadingRow, 
    WithValidation, 
    SkipsOnError, 
    SkipsOnFailure, 
    SkipsEmptyRows,
    WithEvents
{
    use Importable, SkipsErrors, SkipsFailures, RegistersEventListeners;
    
    /**
     * @var int
     */
    protected $rowsProcessed = 0;
    
    /**
     * @var int
     */
    protected $rowsFailed = 0;
    
    /**
     * Process the imported data
     *
     * @param Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            return;
        }
        
        DB::beginTransaction();
        
        try {
            foreach ($rows as $row) {
                try {
                    $this->processRow($row);
                    $this->rowsProcessed++;
                } catch (\Exception $e) {
                    $this->rowsFailed++;
                    $this->onError($e, $this->rowsProcessed + $this->rowsFailed);
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Process a single row
     *
     * @param Collection $row
     * @return void
     */
    abstract protected function processRow(Collection $row);

    /**
     * Get validation rules for the import
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Get custom validation messages
     *
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [];
    }

    /**
     * Get custom validation attributes
     *
     * @return array
     */
    public function customValidationAttributes(): array
    {
        return [];
    }
    
    /**
     * Register events
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                DB::beginTransaction();
            },
            AfterImport::class => function (AfterImport $event) {
                DB::commit();
            },
        ];
    }
}
