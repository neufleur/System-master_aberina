<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //初期テーブル作成
        DB::table('users')->insert([
            [ 'over_name' => '阿部',
            'under_name' => '莉奈',
            'over_name_kana' => 'アベ',
            'under_name_kana' => 'リナ',
            'mail_address' => 'abe@icloud.com',
            'sex' => 1,
            'birth_day' => '1999-09-06',
            'role' => 1,
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
            ]
        ]);

         //初期テーブル作成
         DB::table('users')->insert([
            [ 'over_name' => 'もも',
            'under_name' => 'んが',
            'over_name_kana' => 'モモ',
            'under_name_kana' => 'ンガ',
            'mail_address' => 'momo@icloud.com',
            'sex' => 2,
            'birth_day' => '1992-05-06',
            'role' => 4,
            'password' => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
            ]
        ]);

    }
}

//実際のデータをseederで登録　php artisan migrate