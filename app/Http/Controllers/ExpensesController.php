<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Http\Requests\ExpenseSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Services\Search\ExpenseSearch;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    public function list(ExpenseSearchRequest $request)
    {
        $expenseSearch = new ExpenseSearch($request);

        return response()->json(BaseCollection::make($expenseSearch->getEntries(), ExpenseResource::class));
    }

    public function show(Expense $expense): JsonResponse
    {
        return response()->json(ExpenseResource::make($expense));
    }

    public function store(ExpenseRequest $request)
    {
        $expense = Expense::create($request->validated());

        return response()->json(['message' => __('app.success'), 'id' => $expense->id], 201);
    }


    public function update(ExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->validated());

        return response()->json(['message' => __('app.success')]);
    }

    public function destroy(Expense $expense)
    {
        DB::transaction(function () use ($expense) {
            $expense->delete();
        });

        return response()->json(['message' => __('app.success')]);
    }
}
