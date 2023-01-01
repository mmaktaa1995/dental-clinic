<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $params = [
            'order_column' => $request->input('order_column', 'created_at'),
            'order_dir' => $request->input('order_dir', 'desc'),
            'per_page' => $request->input('per_page', 10),
            'fromDate' => $request->input('fromDate', null),
            'toDate' => $request->input('toDate', null),
            'query' => $request->input('query', null),
        ];

        $data = Service::getAll($params);
        return response()->json(BaseCollection::make($data, ServiceResource::class));
    }

    /**
     * @param \App\Models\Service $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Service $service): \Illuminate\Http\JsonResponse
    {
        return response()->json(ServiceResource::make($service));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ServiceRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request)
    {
        Service::create($request->validated());
        return response()->json(['message' => __('app.success')], 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ServiceRequest $request
     * @param \App\Models\Service $service
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());
        return response()->json(['message' => __('app.success')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Service $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Service $service)
    {
        try {
            $service->delete();
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
        return response()->json(['message' => __('app.success')]);
    }
}
