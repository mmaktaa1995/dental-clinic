<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
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
            'section_id' => ['required', 'numeric', 'exists:sections,id'],
            'section_no' => ['required', 'numeric', 'exists:sections,section_no'],
            'instructor_id' => ['required', 'numeric', 'exists:instructors,id'],
            'course_id' => ['required', 'numeric', 'exists:courses,id'],
        ];
    }
}
