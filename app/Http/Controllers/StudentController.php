<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentRequest;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\StudentResource;
use App\Models\Student;

class StudentController extends Controller
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
            BaseCollection::make(Student::getAll($params), StudentResource::class),
            200
        );
    }

    public function myCourses(Request $request)
    {
        $params = [
            'order_column' => $request->input('order_column', 'id'),
            'order_dir' => $request->input('order_dir', 'asc'),
            'per_page' => $request->input('per_page', 10),
            'fromDate' => $request->input('fromDate', null),
            'toDate' => $request->input('toDate', null),
        ];
        return response()->json(
            BaseCollection::make(Student::getMyCourses($params), EnrollmentResource::class),
            200
        );
    }

    public function destroy(Student $student)
    {
        try {
            $student->delete();
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        return response(['message' => 'Deleted Successfully!']);
    }

    public function show(Student $student)
    {
        return response($student);
    }

    public function store(StudentRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $student = Student::create($data);
        return response(['message' => 'Created Successfully!', 'item' => $student]);
    }

    public function enroll(EnrollmentRequest $request)
    {
        $data = $request->validated();
        $item = request()->user()->enrollments()->create($data);
        return response(['message' => 'Created Successfully!', 'item' => $item]);
    }

    public function update(Student $student, StudentRequest $request)
    {
        $data = $request->validated();
        $student->update($data);

        return response(['message' => 'Updated Successfully!', 'item' => $student]);
    }
}
