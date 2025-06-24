<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions = [
            [
                'id' => 1,
                'name' => 'users_manage',
                'guard_name' => 'user',
                'ja' => '管理者全管理',
                'color' => 'success',
                'is_only_system_admin' => 0,
                'sequence' => 1001,
                'created_at' => '2023-09-01 00:00:00',
                'updated_at' => '2023-09-01 00:00:00'
            ],
            [
                'id' => 2,
                'name' => 'only_user_manage',
                'guard_name' => 'user',
                'ja' => '管理者管理',
                'color' => 'dark',
                'is_only_system_admin' => 0,
                'sequence' => 1002,
                'created_at' => '2023-09-01 00:00:00',
                'updated_at' => '2023-09-01 00:00:00'
            ],
            [
                'id' => 3,
                'name' => 'permissions_control',
                'guard_name' => 'user',
                'ja' => '管理者管理 パーミッション',
                'color' => 'warning',
                'is_only_system_admin' => 0,
                'sequence' => 1010,
                'created_at' => '2023-09-01 00:00:00',
                'updated_at' => '2023-09-01 00:00:00'
            ],
            [
                'id' => 4,
                'name' => 'roles_control',
                'guard_name' => 'user',
                'ja' => '管理者管理 権限',
                'color' => 'warning',
                'is_only_system_admin' => 0,
                'sequence' => 1020,
                'created_at' => '2023-09-01 00:00:00',
                'updated_at' => '2023-09-01 00:00:00'
            ],
            [
                'id' => 5,
                'name' => 'users_control',
                'guard_name' => 'user',
                'ja' => '管理者管理 管理者',
                'color' => 'warning',
                'is_only_system_admin' => 0,
                'sequence' => 1030,
                'created_at' => '2023-09-01 00:00:00',
                'updated_at' => '2023-09-01 00:00:00'
            ],
            [
                'id' => 6,
                'name' => 'user_create',
                'guard_name' => 'user',
                'ja' => '管理者管理 管理者 新規作成',
                'color' => 'info',
                'is_only_system_admin' => 0,
                'sequence' => 1031,
                'created_at' => '2023-09-01 00:00:00',
                'updated_at' => '2023-09-01 00:00:00'
            ],
            [
                'id' => 7,
                'name' => 'user_edit',
                'guard_name' => 'user',
                'ja' => '管理者管理 管理者 編集',
                'color' => 'info',
                'is_only_system_admin' => 0,
                'sequence' => 1032,
                'created_at' => '2023-09-01 00:00:00',
                'updated_at' => '2023-09-01 00:00:00'
            ],
            [
                'id' => 8,
                'name' => 'user_destroy',
                'guard_name' => 'user',
                'ja' => '管理者管理 管理者 削除',
                'color' => 'danger',
                'is_only_system_admin' => 0,
                'sequence' => 1033,
                'created_at' => '2023-09-01 00:00:00',
                'updated_at' => '2023-09-01 00:00:00'
            ],
            [
                'id' => 9,
                'name' => 'show_job_error',
                'guard_name' => 'user',
                'ja' => 'JobError表示',
                'color' => 'danger',
                'is_only_system_admin' => 1,
                'sequence' => 1040,
                'created_at' => '2024-02-01 00:00:00',
                'updated_at' => '2024-02-01 00:00:00'
            ]
        ];

        Artisan::call('cache:clear');
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach($permissions as $p) {
            Permission::create([
                'id' => $p['id'],
                'name' => $p['name'],
                'guard_name' => $p['guard_name'],
                'ja' => $p['ja'],
                'color' => $p['color'],
                'is_only_system_admin' => $p['is_only_system_admin'],
                'sequence' => $p['sequence'],
                'created_at' => $p['created_at'],
                'updated_at' => $p['updated_at'],
            ]);
        }
    }
}
