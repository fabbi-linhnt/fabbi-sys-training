<?php

use Illuminate\Database\Seeder;

class Role_PermissionSeeder extends Seeder
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
            DB::table('role_permission')->insert(
                [
                    'role_id'=>random_int(1,10),
                    'permission_id'=>random_int(1,10),
                    
                ]
            );
        }
    }
}
