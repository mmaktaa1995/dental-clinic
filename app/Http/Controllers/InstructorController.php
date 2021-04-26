<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\InstructorRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\InstructorResource;
use App\Models\Instructor;
class InstructorController extends Controller
{
    public function index(Request $request)
    {
        $params = [
            'order_column' => $request->input('order_column', 'id'),
            'order_dir' => $request->input('order_dir', 'asc'),
            'per_page' => $request->input('per_page', 10),
            'fromDate' => $request->input('fromDate', null),
            'toDate' => $request->input('toDate', null),
            'query' => $request->input('query', null),
        ];
        return response()->json(
            BaseCollection::make(Instructor::getAll($params), InstructorResource::class),
            200
        );
    }

    public function destroy(Instructor $instructor)
    {
        try {
            $instructor->delete();
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        return response(['message' => 'Deleted Successfully!']);
    }

    public function show(Instructor $instructor)
    {
        return response($instructor);
    }

    public function store(InstructorRequest $request)
    {
        $data = $request->validated();
        $instructor = Instructor::create($data);
        return response(['message' => 'Created Successfully!', 'item' => $instructor]);
    }

    public function update(Instructor $instructor, InstructorRequest $request)
    {
        $data = $request->validated();
        $instructor->update($data);

        return response(['message' => 'Updated Successfully!', 'item' => $instructor]);
    }
}
