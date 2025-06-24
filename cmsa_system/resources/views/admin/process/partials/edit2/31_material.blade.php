<div class="card">
  <div class="card-header bg-secondary">
    <span class="card-title">材料費</span>
  </div>
  <div class="card-body">
    <div class="c-grid c-grid--material --edit2">
      <div></div>
      {{-- 規格 --}}
      <div class="text-bold text-center">
        {{ __('admin.process_item.material.standard') }}
      </div>
      {{-- 期間内使用数量 --}}
      <div class="text-bold text-center">
        {{ __('admin.process_item.material.usage') }}
      </div>
      {{-- 累計使用数量 --}}
      <div class="text-bold text-center">
        {{ __('admin.process_item.material.usage_total') }}
      </div>
      {{-- 予算数量×進捗率 --}}
      <div class="text-bold text-center">
        {{ __('admin.process_item.rate.budget_progress') }}
      </div>
      {{-- ロス率 --}}
      <div class="text-bold text-center">
        {{ __('admin.process_item.material.loss_rate') }}
      </div>
    </div>
    @php($class = ['form-control', 'text-right'])
    @foreach($expense_items->where('cost_type', 1) as $item)
      <div class="c-grid c-grid--material --edit2 js-calculate-wrapper" data-process-item-id="{{ $item->values['process_item_id'] }}" data-cost-type="1">

        {{-- 施工期間内使用数量 --}}
        @php($f_name = 'process_term_usage_id')
        @php($name = 'items[' . $item->id . '][' . $f_name . ']')
        {{ html()->hidden($name, $item->values[$f_name]) }}

        {{-- 工程費用項目 --}}
        @php($f_name = 'process_item_id')
        @php($name = 'items[' . $item->id . '][' . $f_name . ']')
        {{ html()->hidden($name, $item->values[$f_name]) }}

        <div>
          {{ $item->title }}
        </div>
        {{-- 規格 --}}
        <div>
          <div class="input-group c-output">
            <div class="form-control c-standard">
              {{ $item->values['standard_label'] }}
            </div>
          </div>
        </div>
        {{-- 期間内使用数量 --}}
        <div>
          @php($f_name = 'usage')
          @php($name = 'items[' . $item->id . '][' . $f_name . ']')
          @php($value = $item->values[$f_name])
          @php($step = 0.01)
          <div class="input-group">
            @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-usage']))
            {{ html()->number($name, $value, 0, null, $step)->attributes(['min' => 0])->class($class2) }}
            <div class="input-group-append">
              <span class="input-group-text">{!! $item->values['unit_html'] ?? '-' !!}</span>
            </div>
          </div>
        </div>
        {{-- 累計使用数量 --}}
        <div>
          <div class="input-group c-output">
            @php($class2 = array_merge($class, ['js-calculate-usage-sum']))
            <div class="{{ implode(' ', $class2) }}">
              {{ $item->values['usage_sum'] }}
            </div>
            <div class="input-group-append">
              <span class="input-group-text">{!! $item->values['unit_html'] ?? '-' !!}</span>
            </div>
          </div>
        </div>
        {{-- 予算数量×進捗率 --}}
        <div>
          <div class="input-group c-output">
            <div class="{{ implode(' ', $class) }}">
              {{ $item->values['budget_progress'] }}
            </div>
            <div class="input-group-append">
              <span class="input-group-text">{!! $item->values['unit_html'] ?? '-' !!}</span>
            </div>
          </div>
        </div>
        {{-- ロス率 --}}
        <div>
          <div class="input-group c-output">
            @php($class2 = array_merge($class, ['js-calculate-loss-ratio']))
            <div class="{{ implode(' ', $class2) }}">
              {{ $item->values['loss_ratio'] }}
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
