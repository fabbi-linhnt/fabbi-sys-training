<?php

use Illuminate\Database\Seeder;

class User_CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 10; $i++) { 
            # code...
            DB::table('user_course')->insert(
                [
                    'user_id'=>random_int(1,10),
                    'course_id'=>random_int(1,10),
                    'status'=>Str::random(10),
                ]
            );
        }
    }
}
