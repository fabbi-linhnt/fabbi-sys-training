<?php

use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
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
            DB::table('subjects')->insert(
                [
                    'name'=>Str::random(10),
                    'description'=> Str::random(10),
                    'is_active'=>(bool)rand(0,1),
                ]
            );
        }
    }
}
