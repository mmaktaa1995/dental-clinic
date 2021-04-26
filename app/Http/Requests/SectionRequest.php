<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'section_no' => [$this->segment(2) ? 'nullable' : 'required', 'numeric', 'unique:sections,section_no'],
            'room_no' => ['required', 'numeric'],
            'time' => ['required', 'date_format:H:i'],
            'instructor_id' => ['required', 'numeric', 'exists:instructors,id'],
            'course_id' => ['required', 'numeric', 'exists:courses,id'],
        ];
    }
}
