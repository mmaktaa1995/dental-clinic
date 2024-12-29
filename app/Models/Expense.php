<?php

namespace App\Models;

use App\Traits\SearchQuery;
use Eloquent;

/**
 * @mixin IdeHelperExpense
 */
class Expense extends BaseModel
{
    protected $fillable = ['date', 'description', 'name', 'amount', 'user_id'];
    protected $table = "financial_expenses";

}
