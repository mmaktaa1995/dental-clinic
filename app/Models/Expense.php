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
 * @method static \Illuminate\Database\Eloquent\Builder|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereUpdatedAt($value)
 * @mixin Eloquent
 */

class Expense extends Eloquent
{
    use SearchQuery;

    public static $searchableFields = ['name'];
    protected $fillable = ['date', 'description', 'name', 'amount'];
    protected $table = "financial_expenses";
    public static $dateColumnFiltered = 'date';

}
