<?php

use Illuminate\Database\Seeder;

class User_SubjectSeeder extends Seeder
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
            DB::table('user_subject')->insert(
                [
                    'user_id'=>random_int(1,10),
                    'subject_id'=>random_int(1,10),
                    'status'=>Str::random(10),
                ]
            );
        }
    }
}
