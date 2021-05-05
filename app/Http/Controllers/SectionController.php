<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $params = [
            'order_column' => $request->input('order_column', 'id'),
            'order_dir' => $request->input('order_dir', 'desc'),
            'per_page' => $request->input('per_page', 10),
            'fromDate' => $request->input('fromDate', null),
            'toDate' => $request->input('toDate', null),
            'query' => $request->input('query', null),
        ];
        return response()->json(
            BaseCollection::make(Section::getAll($params), SectionResource::class),
            200
        );
    }

    public function destroy(Section $section)
    {
        try {
            $section->delete();
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        return response(['message' => 'Deleted Successfully!']);
    }

    public function show(Section $section)
    {
        $section->time = date('h:i' ,strtotime($section->time));
        return response($section);
    }

    public function store(SectionRequest $request)
    {
        $data = $request->validated();
        $section = Section::create($data);
        return response(['message' => 'Created Successfully!', 'item' => $section]);
    }

    public function update(Section $section, SectionRequest $request)
    {
        $data = $request->validated();
        $section->update($data);

        return response(['message' => 'Updated Successfully!', 'item' => $section]);
    }
}
