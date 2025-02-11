<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genres')->insert([
            'name' => 'Comedy'
        ]);

        DB::table('genres')->insert([
            'name' => 'Mystery'
        ]);

        DB::table('genres')->insert([
            'name' => 'Suspense'
        ]);

        DB::table('genres')->insert([
            'name' => 'Romance'
        ]);

        DB::table('genres')->insert([
            'name' => 'Action'
        ]);

        DB::table('genres')->insert([
            'name' => 'Animation'
        ]);

        DB::table('genres')->insert([
            'name' => 'Horror'
        ]);

        DB::table('genres')->insert([
            'name' => 'Family'
        ]);

        DB::table('genres')->insert([
            'name' => 'Adventure'
        ]);

        DB::table('genres')->insert([
            'name' => 'Science fiction'
        ]);
    }
}
