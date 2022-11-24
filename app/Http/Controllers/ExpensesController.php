<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Exception;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $params = [
            'order_column' => $request->input('order_column', 'date'),
            'order_dir' => $request->input('order_dir', 'desc'),
            'per_page' => $request->input('per_page', 10),
            'fromDate' => $request->input('fromDate', null),
            'toDate' => $request->input('toDate', null),
            'query' => $request->input('query', null),
        ];

        $data = Expense::getAll($params);
        $data = collect(BaseCollection::make($data, ExpenseResource::class));
        return response()->json($data);
    }

    /**
     * @param \App\Models\Expense $expense
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Expense $expense): \Illuminate\Http\JsonResponse
    {
        return response()->json(ExpenseResource::make($expense), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ExpenseRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExpenseRequest $request)
    {
        Expense::create($request->validated());
        return response()->json(['message' => __('app.success')], 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ExpenseRequest $request
     * @param \App\Models\Expense $expense
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->validated());
        return response()->json(['message' => __('app.success')], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Expense $expense
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Expense $expense)
    {
        try {
            $expense->delete();
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
        return response()->json(['message' => __('app.success')]);
    }
}
