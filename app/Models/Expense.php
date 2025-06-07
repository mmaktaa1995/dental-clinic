<?php

namespace App\Models;

use App\Traits\SearchQuery;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperExpense
 */
class Expense extends BaseModel
{
    use HasFactory;

    protected $fillable = ['date', 'description', 'name', 'amount', 'user_id'];
    protected $table = "financial_expenses";
}
