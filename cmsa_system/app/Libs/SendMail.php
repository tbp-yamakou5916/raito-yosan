<?php

namespace App\Libs;

use App\Jobs\MailJob;
use Illuminate\Support\Facades\Lang;

class SendMail {
  private $category;
  private $model;
  private $lang;
  private $direction;
  private $options;
  private $mails;

  private $admin;
  private $customer;

  public function __construct()
  {
    // 管理画面用guard
    $this->admin = 'user';
    // 顧客用guard
    $this->customer = 'customer';
  }
  /**
   * パラメータを設定
   */
  public function setParams($items) {
    // 送信先
    $targets = [];
    $isUser = $items['isUser'] ?? false;
    $isCustomer = $items['isCustomer'] ?? false;
    if($isUser) {
      $targets[] = $this->admin;
    }
    if($isCustomer) {
      $targets[] = $this->customer;
    }
    // カテゴリ
    $this->category = $items['category'] ?? $items['lang'] ?? null;
    // 言語ファイル
    $this->lang = $lang = $items['lang'] ?? null;
    // 対象モデル
    $this->model = $items['model'] ?? null;
    // オプション
    $this->options = $items['options'] ?? [];
    // メール設定
    $this->setMails();

    foreach ($targets as $direction) {
      $this->direction = $direction;
      // 件名
      $items['subject'] = $this->getSubject();

      // 宛先
      $items['to'] = $this->getTo();

      // 送信元
      $items['from'] = $this->getFrom();

      // 他送信先設定
      foreach(['reply_to', 'cc', 'bcc'] as $name) {
        $lang_name = 'mail.' . $lang . '.' . $direction . '.' . $name;
        if(Lang::has($lang_name)) {
          $items[$name] = __($lang_name);
        }
      }

      // メール用データ
      $items['params'] = $this->getParams();

      // 表示項目
      $items['name'] = $this->model->name;
      $items['email'] = $this->model->email;

      // メールblade
      $items['view'] = 'mails.' . $direction . '.' . $lang;

      MailJob::dispatch($items);
    }
  }

  /**
   * メールアドレスの設定
   * @return void
   */
  private function setMails(): void
  {
    $this->mails = [
      'user' => [
        'email' => __('site_data.mails.0.email'),
        'name' => __('site_data.title'),
      ],
      'customer' => [
        'email' => $this->model->email,
        'name' => $this->model->name,
      ],
      'noreply' => [
        'email' => __('site_data.mails.noreply.email'),
        'name' => __('site_data.title'),
      ]
    ];
  }

  /**
   * 件名設定
   * @return string
   */
  private function getSubject(): string
  {
    return __('mail.' . $this->lang . '.' . $this->direction . '.title');
  }

  /**
   * 宛先
   * @return array|array[]
   */
  private function getTo(): array
  {
    return [$this->mails[$this->direction]];
  }

  /**
   * 送信元設定
   * @return array|array[]
   */
  private function getFrom(): array
  {
    if(Lang::has('mail.' . $this->lang . '.' . $this->direction . '.mail.from')) {
      $from = __('mail.' . $this->lang . '.' . $this->direction . '.mail.from');
    } else {
      $direction = $this->direction == $this->admin ? $this->customer : 'noreply';
      $from = $this->mails[$direction];
    }

    return $from;
  }

  /**
   * body用パラメータ設定
   * @return array|array[]
   */
  private function getParams(): array
  {
    return $this->options;
  }
}
