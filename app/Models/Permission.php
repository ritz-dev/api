<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "permissions";

    protected $fillable = [
        "slug",
        "name"
    ];
}
