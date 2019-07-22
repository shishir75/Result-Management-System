<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'role_id' => 1,
            'name' => 'Md. VC Office',
            'username' => 'vc-office',
            'email' => 'vc-office@gmail.com',
            'password' => bcrypt(12345678),
        ]);


        DB::table('users')->insert([
            'role_id' => 2,
            'name' => 'Md. Exam Controller',
            'username' => 'exam-controller',
            'email' => 'exam-controller@gmail.com',
            'password' => bcrypt(12345678),
        ]);


        DB::table('users')->insert([
            'role_id' => 3,
            'name' => 'Md. Dept Office',
            'username' => 'dept-0ffice',
            'email' => 'dept-0ffice@gmail.com',
            'password' => bcrypt(12345678),
        ]);

        DB::table('users')->insert([
            'role_id' => 4,
            'name' => 'Md. Teacher',
            'username' => 'teacher',
            'email' => 'teacher@gmail.com',
            'password' => bcrypt(12345678),
        ]);

        DB::table('users')->insert([
            'role_id' => 5,
            'name' => 'Md. Student',
            'username' => 'student',
            'email' => 'student@gmail.com',
            'password' => bcrypt(12345678),
        ]);


    }
}
