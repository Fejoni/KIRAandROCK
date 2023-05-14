<?php

use Database\Seeders\FaqSeeder;
use Database\Seeders\GenreSeeder;
use Database\Seeders\MoodSeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\UsageTypeSeeder;
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
         $this->call(UsersTableSeeder::class);
         $this->call(FaqSeeder::class);
         $this->call(GenreSeeder::class);
         $this->call(MoodSeeder::class);
         $this->call(TagSeeder::class);
         $this->call(UsageTypeSeeder::class);
    }
}
class UsersTableSeeder extends Seeder {

}