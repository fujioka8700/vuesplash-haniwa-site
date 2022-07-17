<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
          'name' => '田中太郎',
          'email' => 'a@a.com',
          'password' => Hash::make('aaaaaaaa')
        ]);

        User::create([
          'name' => '鈴木花子',
          'email' => 'b@b.com',
          'password' => Hash::make('bbbbbbbb')
        ]);
    }
}
