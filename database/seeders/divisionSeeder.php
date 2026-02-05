<?php

namespace Database\Seeders;

use App\Models\division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class divisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        division::insert([
            [
                'id' => Str::uuid(),
                'name' => 'Mobile Apps'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'QA'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Full Stack'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Backend'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Frontend'
            ],
            [
                'id' => Str::uuid(),
                'name' => 'UI/UX Designer'
            ]
        ]);
    }
}
