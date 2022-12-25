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
            'date' => $request->input('date', null),
            'fromDate' => $request->input('fromDate', null),
            'toDate' => $request->input('toDate', null),
            'query' => $request->input('query', null),
        ];

        $totalExpenses = Expense::query()
            ->when($params['fromDate'] && !$params['toDate'], function ($query) use ($params) {
                $query->whereDate('date', '>=', $params['fromDate']);
            })
            ->when($params['toDate'] && !$params['fromDate'], function ($query) use ($params) {
                $query->whereDate('date', '<=', $params['toDate']);
            })
            ->when($params['toDate'] && $params['fromDate'], function ($query) use ($params) {
                $query->whereBetween('date', [$params['fromDate'], $params['toDate']]);
            })
            ->when($params['date'] ?? false, function ($query) use ($params) {
                $query->whereDate('date', $params['date']);
            })
            ->when($params['query'], function ($query) use ($params) {
                $query->where('name', 'like', "%{$params['query']}%");
            })
            ->select([\DB::raw("SUM(amount) as value")])
            ->value('value');

        $data = Expense::getAll($params);
        $data = collect(BaseCollection::make($data, ExpenseResource::class))->merge(['totalValues' => number_format($totalExpenses)]);
        return response()->json($data);
    }

    /**
     * @param \App\Models\Expense $expense
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Expense $expense): \Illuminate\Http\JsonResponse
    {
        return response()->json(ExpenseResource::make($expense));
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
        return response()->json(['message' => __('app.success')]);
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
