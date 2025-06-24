<?php

namespace App\Models\User;

use App\Models\Traits\Common;
use App\Models\Traits\FootPrint;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    use FootPrint, Common, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'guard_name',
        'ja',
        'color',
        'is_only_system_admin',
        'sequence',
        'invalid',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    // route/web.php
    public $route = 'admin.user.permission.';

    /*********************************************************
     * リレーション
     */

    /*********************************************************
     * アクセサ／ミューテタ（ABC順）
     */

    /*********************************************************
     * 関数
     */
}
