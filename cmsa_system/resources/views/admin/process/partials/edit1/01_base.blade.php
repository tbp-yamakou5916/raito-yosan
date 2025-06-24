<div class="card">
  <div class="card-header bg-secondary">
    <span class="card-title">基本情報</span>
  </div>
  <div class="card-body">
    <div class="row">
      {{-- 施工実施 --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'is_not_carried',
          'trans' => 'admin.process',
          'array' => __('array.is_not_carried'),
          'frame' => 'col-3',
      ])
      {{-- 施工予定日数 --}}
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => 'schedule_day',
          'min' => 0,
          'step' => 0.01,
          'trans' => 'admin.process',
          'frame' => 'col-3',
          'is_required' => true,
          'append' => '日',
          'class' => 'js-schedule-day',
      ])

      {{-- 予定施工期間 開始 --}}
      @include('admin.partials.forms', [
          'type' => 'date',
          'name' => 'schedule_start',
          'label' => 'admin.process.schedule',
          'frame' => 'col-3',
          'is_required' => true,
          'is_disabled' => !$datum->schedule_start,
          'prepend' => '開始',
          'class' => 'js-schedule-start',
      ])

      {{-- 予定施工期間 終了 --}}
      @include('admin.partials.forms', [
          'type' => 'date',
          'name' => 'schedule_end',
          'frame' => 'col-3',
          'is_required' => true,
          'is_disabled' => !$datum->schedule_end,
          'prepend' => '終了',
          'class' => 'js-schedule-end',
          'min' => null
      ])
    </div>
  </div>
</div>
