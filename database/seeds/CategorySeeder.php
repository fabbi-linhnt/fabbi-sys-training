<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
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
            DB::table('categories')->insert(
                [
                    'name'=>Str::random(10),
                    'parent_id'=>random_int(1,10),
                    
                ]
            );
        }
    }
}
