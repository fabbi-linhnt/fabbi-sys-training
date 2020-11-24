<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
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
            DB::table('users')->insert(
                [
                    'name'=>Str::random(10),
                    'email'=>Str::random(10).'@gmail.com',
                    'address'=>Str::random(10),
                    'password'=> Hash::make('password'),
                ]
            );
        }
        DB::table('users')->insert(
            [
                'name'=>Str::random(10),
                'email'=>'nam@gmail.com',
                'address'=>Str::random(10),
                'password'=> Hash::make('123456'),
            ]
        );
    }
}
