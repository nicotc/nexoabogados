<?php

namespace Database\Seeders;

use App\Models\ModelHasRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::create([
            'name' => 'abogado',
            'guard_name' => 'api']);


        \App\Models\Role::create([
            'name' => 'panel',
            'guard_name' => 'api']);


        \App\Models\User::factory(30)->create();

        \App\Models\User::where('id', '>', 5)->each(function ($user) {
            ModelHasRole::create([
                'role_id' => 1,
                'model_id' => $user->id,
                'model_type' => 'App\Models\User',
            ]);
        });


        \App\Models\User::where('id', '<=', 5)->each(function ($user) {
            ModelHasRole::create([
                'role_id' => 2,
                'model_id' => $user->id,
                'model_type' => 'App\Models\User',
            ]);
        });

        \App\Models\Subscription::factory(30)->create();
        










    }


}
