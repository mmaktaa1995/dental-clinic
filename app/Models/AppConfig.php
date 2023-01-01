<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AppConfig
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator getAll($params)
 * @method static \Database\Factories\AppConfigFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig whereValue($value)
 * @mixin \Eloquent
 */
class AppConfig extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function getByKey($key, $default = null)
    {
        return Cache::rememberForever($key, function () use ($key) {
            return AppConfig::query()->where('key', $key)->value('value');
        })??$default;
    }
}
