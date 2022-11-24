<?php

namespace App\Models;

use App\Traits\SearchQuery;
use Eloquent;

/**
 * App/Models/Patient
 *
 * @property int $id
 * @property string $amount
 * @property string $date
 * @property string $description
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator getAll($params)
 */

class Expense extends Eloquent
{
    use SearchQuery;

    protected $fillable = ['date', 'description', 'name', 'amount'];
    protected $table = "financial_expenses";
    public static $dateColumnFiltered = 'date';

}
