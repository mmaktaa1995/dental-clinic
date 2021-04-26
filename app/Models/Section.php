<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_no',
        'room_no',
        'time',
        'instructor_id',
        'course_id',
    ];

    public static function getAll($params)
    {
        $params['fromDate'] = $params['fromDate'] ? date('Y-m-d', strtotime(explode('T', str_replace('"', '', $params['fromDate']))[0])) : null;
        $params['toDate'] = $params['toDate'] ? date('Y-m-d', strtotime(explode('T', str_replace('"', '', $params['toDate']))[0])) : null;
        return self::with(['enrollments', 'instructor','course'])->orderBy($params['order_column'], $params['order_dir'])
            ->when($params['fromDate'] && !$params['toDate'], function ($query) use ($params) {
                $query->whereDate('created_at', '>=', $params['fromDate']);
            })
            ->when($params['toDate'] && !$params['fromDate'], function ($query) use ($params) {
                $query->whereDate('created_at', '>=', $params['toDate']);
            })
            ->when(request()->filled('after'), function ($query) use ($params) {
                $query->whereDate('created_at', '>=', now()->format('Y-m-d H:i'));
            })
            ->when($params['toDate'] && $params['fromDate'], function ($query) use ($params) {
                $query->whereBetween('created_at', [$params['fromDate'], $params['toDate']]);
            })
            ->when($params['query'], function ($query) use ($params) {
                $query->where('section_no', 'like', "%{$params['query']}%")->orWhere('room_no', 'like', "%{$params['query']}%");
            })
            ->paginate($params['per_page']);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'section_no', 'section_no');
    }
}
