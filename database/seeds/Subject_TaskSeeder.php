<?php

use Illuminate\Database\Seeder;

class Subject_TaskSeeder extends Seeder
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
            DB::table('subject_task')->insert(
                [
                    'subject_id'=>random_int(1,10),
                    'task_id'=>random_int(1,10),
                    'status'=>Str::random(10),
                    
                ]
            );
        }
    }
}
