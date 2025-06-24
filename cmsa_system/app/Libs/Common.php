<?php

namespace App\Libs;

use App\Models\User\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Lang;
use Throwable;

class Common {
    /**
     * 日時ラベル
     * @param $time_stamp
     * @param $type
     *
     * @return string|null
     */
    public static function getDateTimeLabel($time_stamp, $type=Null): ?string {
        $str = '';
        if($time_stamp) {
            $time_stamp = new Carbon($time_stamp);
            switch ($type) {
                case 1:
                    break;
                default :
                    $str = $time_stamp->isoFormat('YYYY-MM-DD HH:mm（ddd）');
            }
        }
        return $str ?: Null;
    }

    /**
     * 日付ラベル
     * @param $date
     * @param $type
     *
     * @return string|null
     */
    public static function getDateLabel($date, $type=Null): ?string {
        $str = '';
        if($date) {
            $date = new Carbon($date);
            switch ($type) {
                case 1:
                    break;
                default :
                    $str = $date->isoFormat('YYYY-MM-DD（ddd）');
            }
        }
        return $str ?: Null;
    }

    /**
     * 名前ラベル
     * @param $user_id
     *
     * @return string|null
     */
    public static function  getNameLabel($user_id): ?string {
        $user = User::find($user_id);
        $str = '';
        if($user && $user->name) {
            $str = $user->name;
        }
        return $str ?: Null;
    }

    /**
     * 日時 / 名前 Html
     *
     * @param $date
     * @param $person
     *
     * @return string|null
     */
    public static function makeDateNameHtml($date, $person): ?string {
        if(!$person) return Null;

        $str = $date;
        if($str) $str .= '<br>';
        $str .= $person;

        return '<span class="text-muted text-xs">' . $str . '</span>';
    }

    /**
     * PHPの初期設定を取得
     * @param $option
     *
     * @return array
     * ※ラベルとバイト数
     */
    public static function getPhpIni($option): array {
        $label = ini_get($option);
        // 下記は、$labelが「128M」などと返ってくる想定
        $val = trim($label);
        // この書き方面白い $val[strlen($val) - 1]
        $last = strtolower($val[strlen($val) - 1]);
        $val = substr($val, 0, -1);
        // 下記の処理も面白い
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return [$label, $val];
    }

    /**
     * 使用済みメモリ数を確認
     * @return void
     */
    public static function showUsedMemory(): void {
        dump ('メモリ使用量：'. memory_get_usage() / (1024 * 1024) .'MB');
        dump ('メモリ最大使用量：'. memory_get_peak_usage() / (1024 * 1024) .'MB');
    }

    /**
     * メールアドレス等設定
     * @param $category
     * @param $type
     * @param $user_mail
     * @param $user_name
     *
     * @return array
     */
    public static function setMailHeader($category, $type, $user_mail, $user_name): array {
        // 件名
        $params['subject'] = __('mail.' . $category . '.' . $type . '.title');
        // メールblade
        $params['view'] = 'mails.' . $type . '.' . $category;
        // 宛先
        if($type=='customer') {
            $params['to'] = [
                ['email' => $user_mail, 'name' => $user_name]
            ];
        } else {
            $params['to'] = __('mail.' . $category . '.' . $type . '.mail.to');
        }
        // 送信元
        if($type=='customer') {
            // 送信元
            $params['from'] = __('mail.' . $category . '.' . $type . '.mail.from');
        } else {
            // 送信元
            $params['from'] = [
                'email' => $user_mail,
                'name' => $user_name
            ];
        }
        // 他送信先設定
        foreach(['reply_to', 'cc', 'bcc'] as $name) {
            $lang_name = 'mail.' . $category . '.' . $type . '.mail.' . $name;
            if(Lang::has($lang_name)) {
                $params[$name] = __($lang_name);
            }
        }

        return $params;
    }
}
