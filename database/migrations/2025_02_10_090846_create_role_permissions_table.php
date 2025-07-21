<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role_slug');
            $table->string('permission_slug');

            $table->foreign('role_slug')->references('slug')->on('roles')->onDelete('cascade');
            $table->foreign('permission_slug')->references('slug')->on('permissions')->onDelete('cascade');
            $table->unique(['role_slug','permission_slug']); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
