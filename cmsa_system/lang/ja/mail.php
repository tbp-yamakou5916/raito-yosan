<?php
return [
  'reset_password' => [
    'customer' => [
      'title' => '【ライト工業株式会社】パスワード再設定のお手続き',
    ],
    'user' => [
      'title' => 'ライト工業株式会社　パスワード変更',
    ],
  ],
  'model' => [
    'customer' => [
      'title' => '【ライト工業株式会社】お問合せありがとうございます',
      'mail' => [
        // 1つのみ name省略可 以下同じ
        'from' => [
          'email' => 'reply@xxx.yy.zz',
          'name' => 'ライト工業株式会社'
        ],
        // 複数可能
        'bcc' => [
          ['email' => 'reply@xxx.yy.zz', 'name' => 'ライト工業株式会社']
        ],
      ]
    ],
    'user' => [
      'title' => '問合せがありました',
      'mail' => [
        // 複数可能
        'to' => [
          ['email' => 'reply@xxx.yy.zz', 'name' => 'ライト工業株式会社'],
        ],
        // 複数可能
        'cc' => [
          ['email' => 'reply@xxx.yy.zz', 'name' => 'ライト工業株式会社'],
        ],
      ]
    ],
  ],
];
