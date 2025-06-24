<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libs\Calculator\Edit3Calculator;
use App\Libs\CheckProject;
use App\Libs\projectParams;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChartController extends Controller
{
  private $processes;
  private $result;
  private $terms;
  private $daily_data;
  private $all_start;
  private $nums = [
    'x' => 0,
    'y' => 7,
  ];
  private $total = [
    'plan' => 0,
    'result' => 0,
  ];
  /**
   * @return View|RedirectResponse
   */
  public function index(): View|RedirectResponse
  {
    // 選択状態を確認
    $cp = new CheckProject('chart');
    $result = $cp->check();
    if($result['is_error']) {
      return redirect($result['redirect'])->with('msg_err', $result['message']);
    }

    $pp = new projectParams();
    $free_form = $pp->nowFreeForm();
    $this->processes = $free_form->processes->sortBy('schedule_start');

    // 結果を取得
    $this->setResults();

    // 期間を取得
    $this->setTerms();

    // ラベルの作成
    $labels = $this->setLabels();

    // 期間を取得
    $this->setDailyData();

    $items = [
      'terms' => $this->terms,
      'labels' => $labels,
      'datasets' => $this->getDatasets(),
      'options' => $this->getOptions(),
      'tasks' => $this->getTasks(),
      'footer' => $this->getFooter(),
      'canvas' => [
        'num' => $this->nums['x'],
        // 100はfooterの幅設定となる
        'width' => $this->nums['x'] * 100,
      ],
    ];
    return view('admin.process.chart', $items);
  }

  /**
   * 結果の取得
   *
   * @return void
   */
  private function setResults(): void
  {
    $result = [];
    foreach ($this->processes as $process) {
      // 計画予算
      $e3c = new Edit3Calculator($process->id);
      $result[$process->process_type] = [
        // 費用項目
        'expense_items' => $e3c->getExpenseItems(),
        // 作業情報
        'base_result' => $e3c->getBaseResult(false),
        // 材料ロス/材料費
        'material_result' => $e3c->getMaterialResult(false),
        // 歩掛り
        'productivity_result' => $e3c->getProductivity(false),
        // 外注費
        'outsourcing_result' => $e3c->getOutsourcingResult(false),
      ];
    }

    $this->result = $result;
  }

  /**
   * 各種設定
   *
   * @return void
   */
  private function setTerms(): void
  {
    $terms = [];

    $all_plan_start = null;
    $all_plan_end = null;
    $all_result_start = null;
    $all_result_end = null;
    foreach ($this->processes as $process) {
      $process_type = $process->process_type;
      if(!$all_plan_start) {
        $all_plan_start = $process->schedule_start;
      }
      if(!$all_plan_end || $all_plan_end->lt($process->schedule_end)) {
        $all_plan_end = $process->schedule_end;
      }

      // 計算結果
      $result = $this->result[$process_type];

      // 材料費：着地見込み：予算
      $budget = $result['material_result']['budget'] ?? 0;
      $this->total['plan'] += $budget;
      $daily_price = 0;
      if($process->schedule_day) {
        $daily_price = $budget / $process->schedule_day;
      }
      // 外注費：着地見込み：予算
      $budget = $result['outsourcing_result']['budget'] ?? 0;
      $this->total['plan'] += $budget;
      if($process->schedule_day) {
        // 追加
        $daily_price += $budget / $process->schedule_day;
      }

      // 各工程
      $terms[$process_type] = [
        'label' => $process->processLabel,
      ];
      $terms[$process_type]['plan'] = [
        'start' => $process->schedule_start,
        'end' => $process->schedule_end,
        'day' => $process->schedule_day,
        'day_label' => $process->scheduleDayLabel,
        'term_label' => $process->constructionPeriod2,
        'daily_price' => $daily_price,
      ];

      $process_terms = $process->process_terms->sortBy('start');
      $process_start = null;
      $process_end = null;
      $items = [];
      foreach ($process_terms as $process_term) {
        // 全工程
        if(!$all_result_start || $all_result_start->gt($process_term->start)) {
          $all_result_start = $process_term->start;
        }
        if(!$all_result_end || $all_result_end->lt($process_term->end)) {
          $all_result_end = $process_term->end;
        }
        // 各工程
        if(!$process_start) {
          $process_start = $process_term->start;
        }
        if(!$process_end || $process_end->lt($process_term->end)) {
          $process_end = $process_term->end;
        }
        $items[] = [
          'start' => $process_term->start,
          'end' => $process_term->end,
          'day' => $process_term->real_day,
        ];
      }

      // 工期別
      $term_label = null;
      if($process_start) {
        $term_label .= $process_start->isoFormat('MM/DD（ddd）');
      }
      if($term_label || $process_end) {
        $term_label .= '～';
      }
      if($process_end) {
        $term_label .= $process_end->isoFormat('MM/DD（ddd）');
      }
      // 稼働日数
      $day = $this->result[$process_type]['base_result']['finished_day'];

      // 材料費：材料費計：実績使用額
      $budget = $result['material_result']['amount'] ?? 0;
      $this->total['result'] += $budget;
      $daily_price = 0;
      if($day) {
        $daily_price = $budget / $day;
      }
      // 外注費：外注費：実績
      $budget = $result['outsourcing_result']['record'] ?? 0;
      $this->total['result'] += $budget;
      if($day) {
        // 追加
        $daily_price += $budget / $day;
      }

      // 各工程
      $terms[$process_type]['result'] = [
        'start' => $process_start,
        'end' => $process_end,
        'day' => $day,
        'day_label' => $day ? number_format($day, 2) . '日' : '-',
        'term_label' => $term_label,
        'items' => $items,
        'daily_price' => $daily_price,
      ];
    }

    // 全工程
    $terms['all']['plan']['start'] = $all_plan_start;
    $terms['all']['plan']['end'] = $all_plan_end;
    $terms['all']['result']['start'] = $all_result_start;
    $terms['all']['result']['end'] = $all_result_end;
//    dump('------------------');
//    dump($all_plan_start->format('Y-m-d'));
//    dump($all_plan_end->format('Y-m-d'));
//    dump($all_result_start->format('Y-m-d'));
//    dump($all_result_end->format('Y-m-d'));
//    dd('------------------');
    $this->terms = $terms;
  }

  /**
   * ラベルの作成
   *
   * @return array
   */
  private function setLabels(): array
  {
    $labels = [];
    $start = $this->terms['all']['plan']['start'];
    if($this->terms['all']['plan']['start']->gt($this->terms['all']['result']['start'])) {
      $start = $this->terms['all']['result']['start'];
    }
    $end = $this->terms['all']['plan']['end'];
    if($this->terms['all']['plan']['end']->lt($this->terms['all']['result']['end'])) {
      $end = $this->terms['all']['result']['end'];
    }
    $this->all_start = $start;
    $this->nums['x'] = $start->diffInDays($end);
    $block = $this->nums['x'] + 1;
    foreach(range(1, $block) as $num) {
      $labels[] = $num . '日';
    }

    return $labels;
  }

  /**
   *
   * @return array
   */
  private function getDatasets(): array
  {
    $data1 = [0];
    $data2 = [0];
    foreach ($this->daily_data as $datum) {
      $data1[] = $datum['plan']['rate'];
      $data2[] = $datum['result']['rate'];
    }

    $datasets = [
      [
        'label' => '計画',
        'data' => $data1,
        'borderColor' => 'black',
        'backgroundColor' => 'black',
        'fill' => false,
        'tension' => 0,
        'pointRadius' => 4,
        'yAxisID' => 'y1',
      ],
      [
        'label' => '実施',
        'data' => $data2,
        'borderColor' => 'red',
        'backgroundColor' => 'red',
        'fill' => false,
        'tension' => 0,
        'pointRadius' => 4,
        'yAxisID' => 'y1',
      ]
    ];

    return $datasets;
  }

  /**
   *
   * @return array
   */
  private function getOptions(): array
  {
    $last_data = end($this->daily_data);
    $max = max($last_data['plan']['rate'], $last_data['result']['rate'], 100);
    $options = [
      'responsive' => false,
      'maintainAspectRatio' => false,
      'scales' => [
        'y' => [
          'min' => 0,
          'max' => 7,
          'position' => 'left',
          'ticks' => [
            'autoSkip' => false,
            'display' => false,
          ],
        ],
        'y1' => [
          'min' => 0,
          'max' => $max + 2,
          'grid' => [
            'display' => false,
          ],
          'position' => 'right',
          'ticks' => [
            'autoSkip' => false,
            // jsで設定
            'callback' => null,
          ],
        ],
        'x' => [
          'title' => [
            'display' => false,
            'text' => '日',
          ],
        ],
      ],
      'plugins' => [
        'legend' => [
          'position' => 'top',
        ],
      ],
    ];

    return $options;
  }

  /**
   *
   * @return array
   */
  private function getTasks(): array
  {
    $tasks = [];
    foreach ($this->daily_data as $num => $datum) {
      foreach ($datum as $type => $params) {
        foreach ($params['items'] as $item) {
          $tasks[$num][$type][] = $item['process_type'];
        }
      }
    }

    return $tasks;
  }

  /**
   * daily配列の作成作成
   *
   * @return void
   */
  private function setDailyData(): void
  {
    foreach (range(1, $this->nums['x']) as $num) {
      $data[$num - 1] = [
        'plan' => [
          'rate' => 0,
          'daily_total' => 0,
          'step_total' => 0,
          'items' => [],
        ],
        'result' => [
          'rate' => 0,
          'daily_total' => 0,
          'step_total' => 0,
          'items' => [],
        ]
      ];
    }
    // 基本データ作成
    $all_start = $this->all_start;
    $type = 'plan';
    foreach ($this->terms as $process_type => $params) {
      if($process_type == 'all') continue;
      // 上記配列に 予算
      $start_num = (int) $all_start->diffInDays($params[$type]['start']);
      $day = (int) $params[$type]['day'];
      $day = $day ? $day - 1 : 0;
      foreach(range($start_num, $start_num + $day) as $num) {
        $data[$num][$type]['daily_total'] += $params[$type]['daily_price'];
        $data[$num][$type]['items'][] = [
          'process_type' => $process_type,
          'daily_price' => $params[$type]['daily_price'],
          'start' => $params[$type]['start']->format('m/d'),
          'day' => $day,
          'start_num' => $start_num,
        ];
      }
    }

    $type = 'result';
    foreach ($this->terms as $process_type => $params) {
      if($process_type == 'all') continue;
      foreach ($params[$type]['items'] as $item) {
        $start_num = (int) $all_start->diffInDays($item['start']);
        $day = (int) $item['day'];
        $day = $day ? $day - 1 : 0;
        foreach(range($start_num, $start_num + $day) as $num) {
          $data[$num][$type]['daily_total'] += $params[$type]['daily_price'];
          $data[$num][$type]['items'][] = [
            'process_type' => $process_type,
            'daily_price' => $params[$type]['daily_price'],
            'start' => $item['start']->format('m/d'),
            'day' => $day,
            'start_num' => $start_num,
          ];
        }
      }
    }

    // 累積合計
    $types = ['plan', 'result'];
    $total = [
      'plan' => 0,
      'result' => 0,
    ];
    foreach ($data as $num => $params) {
      foreach ($types as $type) {
        $data[$num][$type]['daily_total'] = round($params[$type]['daily_total'] ?? 0);
        $total[$type] += $data[$num][$type]['daily_total'];
        $data[$num][$type]['step_total'] = $total[$type];
      }
    }

    // 整形
    foreach ($data as $num => $params) {
      // 予算
      foreach ($types as $type) {
        $data[$num][$type]['step_total_label'] = number_format($data[$num][$type]['step_total']) . '円';
        if($this->total[$type]) {
          $data[$num][$type]['rate'] = round($data[$num][$type]['step_total'] / $this->total[$type] * 100);
        }
        $data[$num][$type]['rate_label'] = $data[$num][$type]['rate'] . '%';
      }
    }

    $this->daily_data = $data;
  }
  private function getFooter(): array
  {
    return $this->daily_data;
  }
}
