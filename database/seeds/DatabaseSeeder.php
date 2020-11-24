<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            
            [CategorySeeder::class,
            Course_SubjectSeeder::class,
            CourseSeeder::class,
            PermissionSeeder::class,
            Role_PermissionSeeder::class,
            RoleSeeder::class,
            Subject_TaskSeeder::class,
            SubjectSeeder::class,
            TaskSeeder::class,
            User_CourseSeeder::class,
            User_SubjectSeeder::class,
            User_TaskSeeder::class,
            UserSeeder::class,]

    );
    }
}
