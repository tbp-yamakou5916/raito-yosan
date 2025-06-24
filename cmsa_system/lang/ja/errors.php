<?php
return [
    '400' => [
        'error' => 400,
        'title' => '400 Error Bad Request',
        'h1' => 'HTTP エラー 400 Bad Request',
        'subtitle' => '要求が不正です',
        'comment' => 'お使いのブラウザと通信できないか、ご指定のURLが間違っている可能性があります。'
    ],
    '401' => [
        'error' => 401,
        'title' => '401 Error Unauthorized',
        'h1' => 'HTTP エラー 401 Unauthorized',
        'subtitle' => '認証に失敗しました',
        'comment' => 'リクエストされたリソースを得るために認証が必要です。'
    ],
    '403' => [
        'error' => 403,
        'title' => '403 Error Forbidden',
        'h1' => 'HTTP エラー 403 Forbidden',
        'subtitle' => 'アクセスが認められていません',
        'comment' => '現在、このディレクトリ又はページへのアクセスは禁止されています。'
    ],
    '404' => [
        'error' => 404,
        'title' => '404 Error Not Found',
        'h1' => 'HTTP エラー 404 Not Found',
        'subtitle' => '該当アドレスのページを見つける事ができませんでした',
        'comment' => "サーバーは要求されたリソースを見つけることができませんでした。\nURLのタイプミス、もしくはページが移動または削除された可能性があります。"
    ],
    '419' => [
        'error' => 419,
        'title' => '419 Error Page Expired',
        'h1' => 'HTTP エラー 419 Page Expired',
        'subtitle' => '有効期限切れです',
        'comment' => '再度ログインしてください。'
    ],
    '500' => [
        'error' => 500,
        'title' => '500 Error Internal Server Error',
        'h1' => 'HTTP エラー 500 Internal Server Error',
        'subtitle' => 'サーバー内部でエラーが発生しました',
        'comment' => 'プログラムに文法エラーがあったり、設定に誤りがあった場合などに返されます。\n管理者へ連絡してください。'
    ],
    '503' => [
        'error' => 503,
        'title' => '503 Error Service Unavailable',
        'h1' => 'HTTP エラー 503 Service Unavailable',
        'subtitle' => 'このページへは事情によりアクセスできません',
        'comment' => 'サービスが一時的に過負荷やメンテナンスで使用不可能な状態です。'
    ],
];
