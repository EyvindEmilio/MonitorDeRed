<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class users_seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->truncate();
        DB::table('users')->delete();
        User::create(['id' => 1, 'first_name' => 'Judy Adriana', 'last_name' => 'Quispe Geronimo', 'status' => 'Y', 'user_type' => 1, 'email' => 'ju.adrianis11@gmail.com', 'password' => 'judy']);
        User::create(['id' => 2, 'first_name' => 'Luis Alberto', 'last_name' => 'Ruiz Lara', 'status' => 'Y', 'user_type' => 2, 'email' => 'luisalberto.ruizlara@gmail.com', 'password' => 'luis']);
        User::create(['id' => 3, 'first_name' => 'Cliente', 'last_name' => 'client', 'status' => 'Y', 'user_type' => 3, 'email' => 'e@e.com', 'password' => 'cliente']);
    }
}
