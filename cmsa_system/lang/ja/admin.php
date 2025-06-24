<?php
return [
  /*
  |--------------------------------------------------------------------------
  | プロジェクト
  |--------------------------------------------------------------------------
  |
  | 以下ABC順
  | _menu : サイドメニューなど汎用的に利用
  |
  */
  'project' => [
    '_menu' => '工事選択',
    '_title' => '工事一覧',
    'code' => '工事コード',
    'title' => '工事略名',
    'title2' => '工事',
    'user_id' => '担当工事長',
    'field_user1_id' => '担当ユーザー1',
    'field_user2_id' => '担当ユーザー2',
    'field_user3_id' => '担当ユーザー3',
  ],
  'free_form' => [
    '_menu' => '工区・工種選択',
    '_title' => '工区・工種一覧',
    'title' => '工区名',
    'title2' => '工区・工種',
    'construction_type' => '工種',
    'capabilities_type' => '協力業者の技術力',
    'environment_type' => '運搬環境',
    //［入力］
    'area' => [
      'label' => '施工面積',
      'long' => '① 施工面積',
      'unit' => "m&sup2",
    ],
    //［入力］
    'price' => [
      'label' => '外注基本単価',
      'long' => '② 外注基本単価',
      'unit' => "円",
    ],
    //［入力］
    'thickness' => [
      'label' => '枠内吹付厚さ',
      'long' => '③ 枠内吹付厚さ',
      'unit' => "m",
    ],
    //［計算］
    'frame_area' => [
      'label' => '法枠工面積',
      'long' => '㉑ 法枠工面積',
      'unit' => "m&sup2",
    ],
    //［FfCategory］
    'frame_width' => [
      'label' => '法枠幅',
      'long' => '⑫ 法枠幅',
      'unit' => "m",
    ],
    //［計算］
    'quantity' => [
      'label' => '対象数量（＝法枠延長）',
      'long' => '⑬ 対象数量（＝法枠延長）',
      'unit' => "m",
    ],
    //［計算］
    'frame_num' => [
      'label' => '法枠数',
      'long' => '⑭ 法枠数',
      'unit' => "本",
    ],
    //［計算］
    'one_frame_inner_area' => [
      'label' => '1枠の枠内面積',
      'long' => '⑮ 1枠の枠内面積',
      'unit' => "m&sup2",
    ],
    //［計算］
    'frame_inner_area' => [
      'label' => '枠内面積',
      'long' => '⑯ 枠内面積',
      'unit' => "m&sup2",
    ],
  ],
  'mode' => [
    '_menu' => 'モード切替',
  ],
  'mode1' => [
    '_menu' => '予算入力',
  ],
  'mode2' => [
    '_menu' => '実績入力',
  ],
  'mode3' => [
    '_menu' => '分析実行',
  ],
  'delivery' => [
    '_menu' => '資材搬入登録',
    'delivered_at' => '資材搬入日',
    'name' => '資材名',
    'num' => '搬入数量（累積）',
    'num2' => '使用数量（累積）',
    'num3' => '残量',
  ],
  'csv' => [
    '_menu' => '作業実績登録',
  ],
  'chart' => [
    '_menu' => 'シミュレーション',
  ],
  'process' => [
    '_menu' => '工程',
    'title' => '工程名',
    'schedule' => '予定施工期間',
    'schedule_start' => '予定施工期間 開始',
    'schedule_end' => '予定施工期間 終了',
    'schedule_day' => '施工予定日数',
  ],
  'process_term' => [
    '_menu' => '施工期間',
    'real_day' => '稼働日数',
    'man_hour' => '期間内施工人工',
    'num' => '期間内出来高数量',
    'construction_term' => '施工期間',
    'start' => '施工日 開始',
    'end' => '施工日 終了',
    'total_length' => '総延長(m)',
    'total_area' => '総面積(㎡)',
    'length_within' => '範囲内延長(m)',
    'area_within' => '	範囲内面積(㎡)',
    'rate' => '進捗率(％)',
    'total_length_after' => '施工終了総延長(m)	',
    'total_area_after' => '施工終了総面積(㎡)	',
    'overall_rate' => '全体進捗率(％)	',
    'created_by' => '初回アップロード者',
    'created_at' => '初回アップロード日',
  ],
  'process_item' => [
  ],
  'reset' => [
    '_menu' => '選択リセット',
  ],
  'master' => [
    '_menu' => 'マスター管理',
    'expense_item' => [
      '_menu' => '費用項目マスター',
      'title' => '費用項目名',
      'num_text' => '数量意味',
      // 材料費 / 外注費
      'default_num' => '［デ］数量',
      // 材料費
      'default_rate' => '［デ］ロス率',
      // 外注費
      'default_rate2' => '［デ］作業割合/日',
      'default_popularity' => '［デ］想定人工',
    ],
    'unit' => [
      '_menu' => '単位マスター',
      'title' => '単位',
      'is_custom' => 'カスタム表示フラグ',
    ],
    'expense_custom_item' => [
      '_menu' => 'カスタム費用項目',
      'title' => 'カスタム費用項目名',
    ],
    'standard' => [
      '_menu' => '規格マスター',
      'title' => '規格名',
    ],
    'ff_default' => [
      '_menu' => '法枠タイプデフォルト',
    ],
    'ff_category' => [
      '_menu' => '法枠タイプ',
      'title' => '法枠タイプ名',
      'width' => [
        'label' => '横枠寸法',
        'unit' => 'mm',
      ],
      'length' => [
        'label' => '縦枠寸法',
        'unit' => 'mm',
      ],
      'frame_width' => [
        'label' => '法枠幅',
        'unit' => 'm',
      ],
      'area' => [
        'label' => '対象面積',
        'unit' => '㎡',
      ],
      'frame' => [
        'label' => 'フレーム材',
        'unit' => 'm',
      ],
      'main_anchor' => [
        'label' => '主アンカー',
        'unit' => '本',
      ],
      'sub_anchor' => [
        'label' => '補助アンカー',
        'unit' => '本',
      ],
      'rebar' => [
        'label' => '鉄筋',
        'unit' => 'kg',
      ],
      'rebar_spec' => '鉄筋仕様',
      'stirrup' => [
        'label' => 'スターラップ',
        'unit' => '組',
      ],
      'stirrup_spec' => 'スターラップ仕様',
    ],
    'location' => [
      '_menu' => '拠点マスター',
      'num' => '拠点ID',
      'title' => '拠点名',
    ],
  ],
  /*
  |--------------------------------------------------------------------------
  | 汎用・管理系
  |--------------------------------------------------------------------------
  */
  'id' => 'ID',
  'memo' => '社内連絡用メモ',
  'sequence' => '並び順',
  'invalid' => '無効フラグ',
  'created_by' => '作成者',
  'updated_by' => '更新者',
  'deleted_by' => '削除者',
  'created_at' => '作成日時',
  'updated_at' => '更新日時',
  'deleted_at' => '削除日時',

  'change_password' => [
    '_menu' => 'パスワード変更',
  ],
  'home' => [
    '_menu' => '公開ページ',
  ],
  'telescope' => [
    '_menu' => 'テレスコープ',
  ],
  'user' => [
    '_menu' => 'ユーザー管理',
    'permission' => [
      '_menu' => 'パーミッション',
      'name' => 'パーミッション名',
      'ja' => '日本語名',
      'is_only_system_admin' => 'システム管理者用',
      'color' => 'バッジ色'
    ],
    'role' => [
      '_menu' => '権限',
      'name' => '権限名',
      'ja' => '日本語名',
      'color' => 'バッジ色',
      'permissions' => 'パーミッション'
    ],
    'user' => [
      '_menu' => '管理者',
      'all_location' => [
        'label' => '全拠点',
        'id' => '999',
        'select' => [9999 => '★全拠点'],
      ],
      'location_id' => '拠点',
      'name' => '名前',
      'email' => 'Email',
      'email_verified_at' => 'Email確認日時',
      'password' => 'パスワード',
      'new_password' => '新パスワード',
      'roles' => '権限',
      'remember_token' => 'パスワードリセットToken',
    ],
  ],
  'failed_job' => [
    '_menu' => 'Jobエラー',
    'uuid' => 'uuid',
    'connection' => 'connection',
    'queue' => 'queue',
    'payload' => 'payload',
    'exception' => 'exception',
    'failed_at' => 'failed_at',
  ],
  'prefecture' => [
    '_menu' => '都道府県',
  ],
];
