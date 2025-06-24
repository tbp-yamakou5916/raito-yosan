<?php

namespace App\Libs\AdminLte;

use App\Libs\projectParams;
use Illuminate\Support\Facades\Lang;
use JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter;

class OriginalLangFilter extends LangFilter
{
  /**
   * Gets the translation for a given key.
   *
   * @param string $key The key to translate
   * @param array $params The additional translation params
   * @return string The translation
   */
  protected function getTranslation($key, $params = []): string {
    // Check for a translation.


    $pp = new projectParams();
    // 現在選択中のプロジェクト
    $mode_num = $pp->nowModeNum();
    // 現在選択中のプロジェクト
    $now_project = $pp->nowProject();
    // 現在選択中のフリーフォーム
    $now_free_form = $pp->nowFreeForm();

    if($key=='project') {
      if($now_project) {
        return $now_project->title;
      } else {
        return '（' . Lang::get('admin.' . $key . '._menu', $params) . '）';
      }
    } elseif($key=='free_form') {
      if($now_free_form) {
        return $now_free_form->title;
      } else {
        return '（' . Lang::get('admin.' . $key . '._menu', $params) . '）';
      }
    } elseif (Lang::has('array.process_type.params.' . $key)) {
      return Lang::get('array.process_type.params.' . $key, $params);
    } elseif (Lang::has('admin.' . $key)) {
      return Lang::get('admin.' . $key . '._menu', $params);
    }

    return $key;
  }
}
