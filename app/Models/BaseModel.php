<?php

namespace App\Models;

/**
 * @mixin IdeHelperBaseModel
 */
class BaseModel extends \Eloquent
{

    protected static function boot()
    {
        parent::boot();

        // Attach the "creating" event
        static::creating(function ($model) {
            // Generate a slug from the title
            if (empty($model->user_id)) {
                $model->user_id = auth()->id();
            }
        });
    }
}
