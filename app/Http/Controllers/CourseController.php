<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\CourseResource;
use App\Http\Resources\SectionResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CourseController extends Controller
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
                BaseCollection::make(Course::getAll($params), CourseResource::class),
                200
            );
        } else {
            abort(401, 'This action is unauthorized');
        }
    }

    public function destroy(Course $course)
    {
        $response = Gate::inspect('isAdmin', \request()->user());
        if ($response->allowed()) {
            try {
                $course->delete();
            } catch (\Exception $exception) {
                return response()->json(['message' => $exception->getMessage()], 500);
            }
            return response(['message' => 'Deleted Successfully!']);
        } else {
            abort(403, 'This action is unauthorized.');
        }
    }

    public function show(Course $course)
    {
        return response($course);
    }

    public function sections(Course $course)
    {
        return response(SectionResource::collection($course->sections()->with('instructor')->get()));
    }

    public function store(CourseRequest $request)
    {
        $response = Gate::inspect('isAdmin', $request->user());
        if ($response->allowed()) {
            $data = $request->validated();
            $course = Course::create($data);
            return response(['message' => 'Created Successfully!', 'item' => $course]);
        } else {
            abort(403, 'This action is unauthorized.');
        }
    }

    public function update(Course $course, CourseRequest $request)
    {
        $response = Gate::inspect('isAdmin', $request->user());
        if ($response->allowed()) {
            $data = $request->validated();
            $course->update($data);

            return response(['message' => 'Updated Successfully!', 'item' => $course]);
        } else {
            abort(403, 'This action is unauthorized.');
        }
    }
}
