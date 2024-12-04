<?php

namespace Database\Seeders;

use Database\Factories\NotebookFactory;
use Illuminate\Database\Seeder;

class NotebookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NotebookFactory::new()->count(100)->create();
    }
}
