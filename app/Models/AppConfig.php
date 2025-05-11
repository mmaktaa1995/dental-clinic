<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAppConfig
 */
class AppConfig extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function getByKey($key, $default = null)
    {
        return Cache::rememberForever($key, function () use ($key) {
            return AppConfig::query()->where('key', $key)->value('value');
        }) ?? $default;
    }
}
