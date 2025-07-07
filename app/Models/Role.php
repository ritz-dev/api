<?php

namespace App\Models;

use App\Models\Permission;
use Ramsey\Uuid\Guid\Guid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "roles";

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

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_slug',
            'permission_slug',
            'slug',   // ⬅ local key on Role
            'slug'    // ⬅ local key on Permission
        );
    }
}
