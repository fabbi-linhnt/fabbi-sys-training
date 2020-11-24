<?php

use Illuminate\Database\Seeder;

class Course_SubjectSeeder extends Seeder
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
            DB::table('course_subject')->insert(
                [
                    'course_id'=>random_int(1,10),
                    'subject_id'=>random_int(1,10),
                    'status'=>Str::random(10),
                ]
            );
        }
    }
}
