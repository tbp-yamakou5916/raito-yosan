<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMstPrefecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('mst_prefectures', function (Blueprint $table) {
            $table->unsignedInteger('id')
                  ->comment('都道府県コード');

            $table->string('name', 20)
                  ->comment('都道府県名');

            $table->string('short', 20)
                  ->comment('都道府県名（都府県なし）');

            $table->string('division', 5)
                  ->nullable()
                  ->comment('都府県');

            $table->string('hash', 20)
                  ->nullable()
                  ->comment('都府県');

            $table->integer('sequence')
                  ->default(0)
                  ->comment('並び順');

            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('mst_prefectures');
    }
}
