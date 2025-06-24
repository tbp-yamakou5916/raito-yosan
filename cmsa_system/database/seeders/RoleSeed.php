<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void {
        $role = Role::create([
        	'name' => 'system_admin',
            'guard_name' => 'user',
	        'ja' => 'システム管理者',
            'color' => 'secondary',
            'sequence' => '100',
        ]);
        $permissions = [
            'users_manage',
            'permissions_control',
            'roles_control',
            'users_control',
            'user_create',
            'user_edit',
            'user_destroy',
            'show_job_error'
        ];
        $role->givePermissionTo($permissions);

        $role = Role::create([
            'name' => 'site_admin',
            'guard_name' => 'user',
            'ja' => 'サイト管理者',
            'color' => 'danger',
            'sequence' => '110',
        ]);
        $permissions = [
            'users_manage',
            'permissions_control',
            'roles_control',
            'users_control',
            'user_create',
            'user_edit',
            'user_destroy',
        ];
        $role->givePermissionTo($permissions);

        $role = Role::create([
            'name' => 'company_admin',
            'guard_name' => 'user',
            'ja' => '企業管理者',
            'color' => 'success',
            'sequence' => '120',
        ]);
        $permissions = [
            'only_user_manage',
        ];
        $role->givePermissionTo($permissions);
    }
}
