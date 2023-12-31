<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ClientSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $now = Carbon::now( 'utc' )->toDateTimeString();

        Client::insert( [
            [
                'name'       => 'MAHART Zrt.',
                'address'    => 'Tápé, Komp u. 1.',
                'type'       => 'company',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'name'       => 'Florin Group Kft.',
                'address'    => 'Szeged, Fonógyári út 65.',
                'type'       => 'company',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'name'       => 'Magyar Antal',
                'address'    => 'Tápé, Barack u. 10.',
                'type'       => 'private person',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ] );

    }
}
