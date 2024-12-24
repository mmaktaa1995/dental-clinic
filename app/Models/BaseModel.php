<?php

namespace App\Models;

/**
 * @mixin IdeHelperBaseModel
 */
class BaseModel extends \Eloquent
{
    public static function create($attributes = [])
    {
        $attributes['user_id'] = auth()->id();
        return parent::create($attributes);
    }
}
