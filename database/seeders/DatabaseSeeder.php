<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\teacher;
use App\Models\admin;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        teacher::factory(5)->create();
        Admin::factory(2)->create();

        $cate = new Category();
        $cate->name = 'Computer Networks';
        $cate->save();

        $cate = new Category();
        $cate->name = 'Computer Graphics';
        $cate->save();

        $cate = new Category();
        $cate->name = 'Artificial Intelligence';
        $cate->save();

        $cate = new Category();
        $cate->name = 'Software Engineering';
        $cate->save();
    }
}
