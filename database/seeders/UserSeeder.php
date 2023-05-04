<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $worker        = Role::where( 'slug', 'worker' )->first();
        $admin         = Role::where( 'slug', 'administrator' )->first();
        $createTasks   = Permission::where( 'slug', 'create-tasks' )->first();
        $manageWorkers = Permission::where( 'slug', 'manage-workers' )->first();

        $user1           = new User();
        $user1->name     = 'John Doe';
        $user1->email    = 'john@doe.com';
        $user1->password = bcrypt( 'password' );
        $user1->save();
        $user1->roles()->attach( $worker );
        $user1->permissions()->attach( $createTasks );

        $user2           = new User();
        $user2->name     = 'Mike Thomas';
        $user2->email    = 'mike@thomas.com';
        $user2->password = bcrypt( 'password' );
        $user2->save();
        $user2->roles()->attach( $worker );
        $user2->permissions()->attach( $createTasks );

        $user3 = User::findOrFail( 1 );
        $user3->roles()->attach( $admin );
        $user3->permissions()->attach( $manageWorkers );
        $user3->permissions()->attach( $createTasks );
    }
}
