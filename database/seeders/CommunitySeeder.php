<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Community;

class CommunitySeeder extends Seeder
{
    public function run()
    {
        Community::create(['name' => 'Community A', 'description' => 'First community']);
        Community::create(['name' => 'Community B', 'description' => 'Second community']);
    }
}