<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade',
        'student_id',
        'course_id',
        'section_no',
    ];

    public static function getAll($params)
    {
        $params['fromDate'] = $params['fromDate'] ? date('Y-m-d', strtotime(explode('T', str_replace('"', '', $params['fromDate']))[0])) : null;
        $params['toDate'] = $params['toDate'] ? date('Y-m-d', strtotime(explode('T', str_replace('"', '', $params['toDate']))[0])) : null;
        return self::with(['student', 'course', 'section.instructor'])->orderBy($params['order_column'], $params['order_dir'])
            ->when($params['fromDate'] && !$params['toDate'], function ($query) use ($params) {
                $query->whereDate('created_at', '>=', $params['fromDate']);
            })
            ->when($params['toDate'] && !$params['fromDate'], function ($query) use ($params) {
                $query->whereDate('created_at', '>=', $params['toDate']);
            })
            ->when($params['toDate'] && $params['fromDate'], function ($query) use ($params) {
                $query->whereBetween('created_at', [$params['fromDate'], $params['toDate']]);
            })
            ->paginate($params['per_page']);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_no', 'section_no');
    }

}
