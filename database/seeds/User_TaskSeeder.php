<?php

use Illuminate\Database\Seeder;

class User_TaskSeeder extends Seeder
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
            DB::table('user_task')->insert(
                [
                    'user_id'=>random_int(1,10),
                    'task_id'=>random_int(1,10),
                    'status'=>random_int(0,1),
                    'report'=>Str::random(1,10),
                    'date_submit' => '2020-10-11',
                    'comment'=>Str::random(1,10),
                ]
            );
        }
    }
}
