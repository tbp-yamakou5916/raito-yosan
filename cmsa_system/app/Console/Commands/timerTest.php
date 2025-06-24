<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class timerTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:timer-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'テスト用 定期実行ツールの動作確認';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 標準出力またはログに出力するメッセージ
        $message = '[' . date('Y-m-d h:i:s') . '] test';

        // INFOレベルでメッセージを出力する
        $this->info( $message );
    }
}
