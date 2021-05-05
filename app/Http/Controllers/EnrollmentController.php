<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseCollection;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $response = Gate::inspect('isAdmin', $request->user());
        if ($response->allowed()) {
            $params = [
                'order_column' => $request->input('order_column', 'id'),
                'order_dir' => $request->input('order_dir', 'desc'),
                'per_page' => $request->input('per_page', 10),
                'fromDate' => $request->input('fromDate', null),
                'toDate' => $request->input('toDate', null),
                'query' => $request->input('query', null),
            ];
            return response()->json(
                BaseCollection::make(Enrollment::getAll($params), EnrollmentResource::class),
                200
            );
        } else {
            abort(401, 'This action is unauthorized');
        }
    }
}
