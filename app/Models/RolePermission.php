<?php

namespace App\Models;

use Ramsey\Uuid\Guid\Guid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolePermission extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "role_permissions";

    protected $fillable = [
        "role_slug",
        "permission_slug",
        "description"
    ];

    protected $hidden = [
        "id",
        "created_at",
        "updated_at",
        "deleted_at"
    ];
}
