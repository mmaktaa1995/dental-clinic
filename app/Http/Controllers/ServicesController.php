<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Http\Requests\ServiceSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\Search\ServiceSearch;
use Illuminate\Http\JsonResponse;

class ServicesController extends Controller
{
    public function list(ServiceSearchRequest $request): JsonResponse
    {
        $filesSearch = new ServiceSearch($request);

        return response()->json(BaseCollection::make($filesSearch->getEntries(), ServiceResource::class));

    }

    public function show(Service $service): JsonResponse
    {
        return response()->json(ServiceResource::make($service));
    }

    public function store(ServiceRequest $request): JsonResponse
    {
        $service = Service::create($request->validated());
        return response()->json(['message' => __('app.success'), 'id' => $service->id], 201);
    }


    public function update(ServiceRequest $request, Service $service): JsonResponse
    {
        $service->update($request->validated());
        return response()->json(['message' => __('app.success')]);
    }

    public function destroy(Service $service): JsonResponse
    {
        \DB::transaction(function () use ($service) {
            $service->delete();
        });
        return response()->json(['message' => __('app.success')]);
    }
}
