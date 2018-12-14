<?php
/**
 * Created by PhpStorm.
 * User: hubert_i
 * Date: 2018-12-14
 * Time: 00:00
 */

namespace App;

use Webpatser\Uuid\Uuid;

trait Uuids
{

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }
}