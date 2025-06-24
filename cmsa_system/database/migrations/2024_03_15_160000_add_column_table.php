<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('ja')
                  ->nullable()
                  ->after('guard_name')
                  ->comment('日本語表記');

            $table->string('color')
                  ->default('info')
                  ->nullable()
                  ->after('ja')
                  ->comment('バッジの色');

            $table->integer('sequence')
                  ->default(0)
                  ->nullable()
                  ->after('color')
                  ->comment('並び順');
            $table->unsignedTinyInteger('invalid')
                  ->nullable()
                  ->default(0)
                  ->after('sequence');
            $table->unsignedInteger('created_by')
                  ->nullable()
                  ->after('invalid');
            $table->unsignedInteger('updated_by')
                  ->nullable()
                  ->after('created_by');
            $table->unsignedInteger('deleted_by')
                  ->nullable()
                  ->after('updated_by');
            $table->softDeletes()
                  ->after('updated_at');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->string('ja')
                  ->nullable()
                  ->after('guard_name')
                  ->comment('日本語表記');

            $table->string('color')
                  ->default('info')
                  ->nullable()
                  ->after('ja')
                  ->comment('バッジの色');

            $table->unsignedTinyInteger('is_only_system_admin')
                  ->default('0')
                  ->nullable()
                  ->after('color')
                  ->comment('システム管理者用');

            $table->integer('sequence')
                  ->default(0)
                  ->nullable()
                  ->after('is_only_system_admin')
                  ->comment('並び順');
            $table->unsignedTinyInteger('invalid')
                  ->nullable()
                  ->default(0)
                  ->after('sequence');
            $table->unsignedInteger('created_by')
                  ->nullable()
                  ->after('invalid');
            $table->unsignedInteger('updated_by')
                  ->nullable()
                  ->after('created_by');
            $table->unsignedInteger('deleted_by')
                  ->nullable()
                  ->after('updated_by');
            $table->softDeletes()
                  ->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('color');
            $table->dropColumn('sequence');
            $table->dropColumn('invalid');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('deleted_by');
            $table->dropSoftDeletes();
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('color');
            $table->dropColumn('is_only_system_admin');
            $table->dropColumn('sequence');
            $table->dropColumn('invalid');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('deleted_by');
            $table->dropSoftDeletes();
        });
    }
}
