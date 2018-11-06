<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'Test',
            'email' =>'admin@mail.com',
            'user_type' =>'1',
            'password' =>bcrypt('123456'),
            'remember_token'=>'UOQyJW3gBk5XDco9G5L0WRTZScPKuHtDmNimNOTzt39bhLuUtEWfvZssqD8q',
            'user_status'=>'Active',
        ]);
    }
}
