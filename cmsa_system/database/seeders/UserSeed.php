<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\User;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void {
        $user = User::create([
            'name' => 'システム管理者',
            'email' => 'system@flying-h.co.jp',
            'password' => bcrypt('fhw20000328')
        ]);
        $user->assignRole('system_admin');

        $user = User::create([
            'name' => 'サイト管理者',
            'email' => 'site@flying-h.co.jp',
            'password' => bcrypt('password')
        ]);
        $user->assignRole('site_admin');

    }
}
