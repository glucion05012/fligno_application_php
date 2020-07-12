<?php

use Illuminate\Database\Seeder;
use App\Profile;

class DummyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Profile::class, 50)->create();
    }
}
