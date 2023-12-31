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

        $worker            = Role::where( 'slug', 'worker' )->first();
        $admin             = Role::where( 'slug', 'administrator' )->first();

        $user1           = new User();
        $user1->name     = 'Gulácsi András';
        $user1->email    = 'gulandras90@gmail.com';
        $user1->password = bcrypt( 'password' );
        $user1->role()->associate( $admin );
        $user1->save();

        $user2           = new User();
        $user2->name     = 'John Doe';
        $user2->email    = 'john@doe.com';
        $user2->password = bcrypt( 'password' );
        $user2->save();
        $user2->role()->associate( $worker );

        $user3           = new User();
        $user3->name     = 'Mike Thomas';
        $user3->email    = 'mike@thomas.com';
        $user3->password = bcrypt( 'password' );
        $user3->save();
        $user3->role()->associate( $worker );

    }
}
