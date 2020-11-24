<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
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
            DB::table('roles')->insert(
                [
                    'name' => Str::random(10),
                    'user_id'=>random_int(1,10),
                    
                ]
            );
        }
    }
}
