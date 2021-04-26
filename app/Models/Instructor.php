<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'address',
        'mobile_number',
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
}
