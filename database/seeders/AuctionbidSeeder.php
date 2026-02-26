<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AuctionBid;
use App\Models\User;

class AuctionbidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AuctionBid::factory(10)
        ->hasAttached(User::factory()->count(3))
        ->create();
    }
}
