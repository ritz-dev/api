<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
   protected $model = Permission::class;

    public function definition(): array
    {
        // Predefined modules and actions
        $modules = ['user', 'post', 'role', 'permission', 'product', 'category'];
        $actions = ['view', 'create', 'update', 'delete'];

        $module = $this->faker->randomElement($modules);
        $action = $this->faker->randomElement($actions);

        $name = "{$module}.{$action}";

        return [
            'name'        => $name,
            'description' => "Allows a user to {$action} a {$module}.",
        ];
    }
}
