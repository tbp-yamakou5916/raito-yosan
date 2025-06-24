<div class="card">
  <div class="card-header bg-secondary">
    <span class="card-title">外注費</span>
  </div>
  <div class="card-body">
    <div class="c-grid c-grid-head c-grid--outsourcing --edit2">
      <div></div>
      {{-- 予算数量 --}}
      <div class="text-bold text-center">
        {{ __('admin.process_item.outsourcing.num') }}
      </div>
      {{-- 期間内出来高数量 --}}
      <div class="text-bold text-center">
        {{ __('admin.process_item.outsourcing.usage') }}
      </div>
      {{-- 累計出来高数量 --}}
      <div class="text-bold text-center">
        {{ __('admin.process_item.outsourcing.usage_total') }}
      </div>
      {{-- 予算数量×進捗率 --}}
      <div class="text-bold text-center">
        @if(in_array($process_type, [1, 2, 7]))
          {{ __('admin.process_item.rate.budget_progress2') }}
        @else
          {{ __('admin.process_item.rate.budget_progress') }}
        @endif
      </div>
      {{-- 実施人工 --}}
      <div class="text-bold text-center">
        {{ __('admin.process_item.outsourcing.man_hour') }}
      </div>
    </div>
    @php($class = ['form-control', 'text-right'])
    @foreach($expense_items->where('cost_type', 2) as $item)
      <div class="c-grid c-grid--outsourcing --edit2 js-calculate-wrapper" data-process-item-id="{{ $item->values['process_item_id'] }}" data-cost-type="2">

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
        {{-- 予定数量 --}}
        <div>
          <div class="input-group c-output">
            <div class="{{ implode(' ', $class) }}">
              {{ $item->values['num'] }}
            </div>
            <div class="input-group-append">
              <span class="input-group-text">{!! $item->unitHtml ?? '-' !!}</span>
            </div>
          </div>
        </div>
        {{-- 期間内出来高数量 --}}
        <div>
          @php($f_name = 'usage')
          @php($name = 'items[' . $item->id . '][' . $f_name . ']')
          @php($value = $item->values[$f_name])
          @php($step = 0.01)
          <div class="input-group">
            @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-usage']))
            {{ html()->number($name, $value, 0, null, $step)->class($class2) }}
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
        {{-- 進捗率 --}}
        {{-- 予算数量×進捗率 --}}
        <div>
          <div class="input-group c-output">
            @php($class2 = $class)
            @if(in_array($process_type, [1, 2, 7]))
              @php($class2 = array_merge($class, ['js-calculate-progress-rate']))
            @endif
            <div class="{{ implode(' ', $class) }}">
              {{ $item->values['budget_progress'] }}
            </div>
            <div class="input-group-append">
              <span class="input-group-text">{!! $item->values['unit_html'] ?? '-' !!}</span>
            </div>
          </div>
        </div>
        {{-- 実施人工 --}}
        <div>
          @php($f_name = 'man_hour')
          @php($name = 'items[' . $item->id . '][' . $f_name . ']')
          @php($value = $item->values[$f_name])
          @php($step = 0.01)
          <div class="input-group">
            @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-man-hour']))
            {{ html()->number($name, $value, 0, null, $step)->class($class2) }}
            <div class="input-group-append">
              <span class="input-group-text">人工</span>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
