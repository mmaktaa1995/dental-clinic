<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'hours',
    ];

    public static function getAll($params)
    {
        $params['fromDate'] = $params['fromDate'] ? date('Y-m-d', strtotime(explode('T', str_replace('"', '', $params['fromDate']))[0])) : null;
        $params['toDate'] = $params['toDate'] ? date('Y-m-d', strtotime(explode('T', str_replace('"', '', $params['toDate']))[0])) : null;
        return self::with('enrollments')->orderBy($params['order_column'], $params['order_dir'])
            ->when($params['fromDate'] && !$params['toDate'], function ($query) use ($params) {
                $query->whereDate('created_at', '>=', $params['fromDate']);
            })
            ->when($params['toDate'] && !$params['fromDate'], function ($query) use ($params) {
                $query->whereDate('created_at', '>=', $params['toDate']);
            })
            ->when($params['toDate'] && $params['fromDate'], function ($query) use ($params) {
                $query->whereBetween('created_at', [$params['fromDate'], $params['toDate']]);
            })
            ->when($params['query'] , function ($query) use ($params) {
                $query->where('title','like',"%{$params['query']}%" );
            })
            ->paginate($params['per_page']);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
