<?php

use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 10 ; $i++) {
            # code...
            DB::table('tasks')->insert(
                [
                    'name'=>Str::random(10),
                    'description'=>Str::random(10),
                    'content'=>Str::random(10),
                    'time'=>rand(1,5),
                    'is_active'=>(bool)rand(0,1),
                ]
            );
        }
    }
}
