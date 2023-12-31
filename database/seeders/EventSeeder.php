<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Event::insert([
            [
                'id' => Str::uuid(),
                'start' => '2023-05-10',
                'end'   => null,
                'description' => 'etiam elit eget natum deseruisse offendit',
                'status' => 'opened',
            ],

            [
                'id' => Str::uuid(),
                'start' => '2023-05-11T08:00:00',
                'end'   => '2023-05-11T16:00:00',
                'description' => 'netus erroribus autem ridiculus idque fermentum',
                'status' => 'opened',
            ],

            [
                'id' => Str::uuid(),
                'start' => '2023-05-12',
                'end'   => '2023-05-14',
                'description' => 'necessitatibus interdum voluptatibus magna nominavi delenit',
                'status' => 'completed',
            ]
        ]);

    }
}
