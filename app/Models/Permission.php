<?php

namespace App\Models;

use Ramsey\Uuid\Guid\Guid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "permissions";

    protected $fillable = [
        "slug",
        "name",
        "description"
    ];

    protected $hidden = [
        "id",
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if(empty($model->slug)){
                $model->slug = (string) Guid::uuid4();
            }
        });
    }
}
