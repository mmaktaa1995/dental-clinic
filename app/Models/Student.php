<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'username',
        'email',
        'first_name',
        'last_name',
        'reg_year',
        'gender',
        'address',
        'mobile_number',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public static function getAll($params)
    {
        $params['fromDate'] = $params['fromDate'] ? date('Y-m-d', strtotime(explode('T', str_replace('"', '', $params['fromDate']))[0])) : null;
        $params['toDate'] = $params['toDate'] ? date('Y-m-d', strtotime(explode('T', str_replace('"', '', $params['toDate']))[0])) : null;
        return self::orderBy($params['order_column'], $params['order_dir'])
            ->when($params['fromDate'] && !$params['toDate'], function ($query) use ($params) {
                $query->whereDate('created_at', '>=', $params['fromDate']);
            })
            ->when($params['toDate'] && !$params['fromDate'], function ($query) use ($params) {
                $query->whereDate('created_at', '>=', $params['toDate']);
            })
            ->when($params['toDate'] && $params['fromDate'], function ($query) use ($params) {
                $query->whereBetween('created_at', [$params['fromDate'], $params['toDate']]);
            })
            ->when($params['query'], function ($query) use ($params) {
                $query->where('first_name', 'like', "%{$params['query']}%")->orWhere('last_name', 'like', "%{$params['query']}%");
            })
            ->paginate($params['per_page']);
    }

    public static function getMyCourses($params)
    {
        $params['fromDate'] = $params['fromDate'] ? date('Y-m-d', strtotime(explode('T', str_replace('"', '', $params['fromDate']))[0])) : null;
        $params['toDate'] = $params['toDate'] ? date('Y-m-d', strtotime(explode('T', str_replace('"', '', $params['toDate']))[0])) : null;
        return request()->user()->enrollments()->with([ 'course', 'section.instructor'])->orderBy($params['order_column'], $params['order_dir'])
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

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
