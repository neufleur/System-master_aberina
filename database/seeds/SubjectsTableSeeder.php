<?php

use Illuminate\Database\Seeder;
use App\Models\User\Subjects;
use Illuminate\Support\Facades\DB;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //subjectsテーブル　国語、数学、英語レコードを作成する
        DB::table('subjects')->insert([
            ['subject' => '国語','created_at' => now()],
            ['subject' => '数学','created_at' => now()],
            ['subject' => '英語','created_at' => now()],
        ]);

    }
}