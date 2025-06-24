<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void {
        Customer::create([
            'name' => 'テストユーザー',
            'email' => 'customer@flying-h.co.jp',
            'password' => bcrypt('password')
        ]);
    }
}
