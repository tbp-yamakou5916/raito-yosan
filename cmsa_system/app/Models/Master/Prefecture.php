<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    protected $table = 'mst_prefectures';

    public $timestamps = false;

    protected $guarded = [
        'id'
    ];

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
