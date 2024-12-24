<?php

namespace App\Models;

use App\Traits\SearchQuery;
use Eloquent;

/**
 * @mixin IdeHelperExpense
 */
class Expense extends BaseModel
{
    use SearchQuery;

    public static $searchableFields = ['name'];
    protected $fillable = ['date', 'description', 'name', 'amount', 'user_id'];
    protected $table = "financial_expenses";
    public static $dateColumnFiltered = 'date';

}
