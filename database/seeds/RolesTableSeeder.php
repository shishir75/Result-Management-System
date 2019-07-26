<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Register',
            'slug' => 'register',
        ]);

        DB::table('roles')->insert([
            'name' => 'Exam Controller',
            'slug' => 'exam-controller',
        ]);

        DB::table('roles')->insert([
            'name' => 'Dept Office',
            'slug' => 'dept-office',
        ]);

        DB::table('roles')->insert([
            'name' => 'Teacher',
            'slug' => 'teacher',
        ]);

        DB::table('roles')->insert([
            'name' => 'Student',
            'slug' => 'student',
        ]);


    }
}
