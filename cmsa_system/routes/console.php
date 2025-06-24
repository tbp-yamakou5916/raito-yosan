<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
*/

// countコマンドを実行して、ログファイルに出力 / 動作確認用
//Schedule::command('command:timer-test')
//  ->everyMinute()
//  ->appendOutputTo(storage_path('logs/timer_test.log'));

// JobWorkerを利用する場合
//Schedule::command('queue:work --tries=3 --stop-when-empty')
//        ->everyMinute()
//        ->appendOutputTo(storage_path('logs/queue_work.log'));

// 毎日深夜にセッションデータを削除する
Schedule::command('command:prune')->daily();

// 期限切れトークンの削除
Schedule::command('auth:clear-resets')->daily();
