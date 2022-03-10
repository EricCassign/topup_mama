<?php

namespace Database\Seeders;

use App\Models\Character;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CharacterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Character::create(
            [
                'name' => '"Balon Greyjoy',
                'gender' => 'Male',
                'created_at' => Carbon::now(),
                'died' => '299 AC, at Pyke'
            ],
        );
        Character::create([
            'name' => 'Walder',
            'gender' => 'Male',
            'created_at' => Carbon::now(),
        ]);
    }
}
