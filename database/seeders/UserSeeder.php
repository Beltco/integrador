<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Admin Belt',
            'email'=>'it@beltcolombia.com',
            'password'=>'$2y$12$yw3EcHYr1xE5vkCr03uNk.yDHMs99W4KQ1li.Nf7Kw4cz.rf1105a'
        ]);
    }
}
