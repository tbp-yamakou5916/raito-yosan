@php($class = ['form-control'])
@php($disables = explode(',', $item->disabled))
<div class="c-grid c-grid--material js-expense-row js-calculate-wrapper"
     data-type="material"
     data-process-id="{{ $item->values['process_id'] }}"
     data-item-id="{{ $item->id }}"
     data-is-custom="{{ (int) $item->values['is_custom'] }}"
>
  {{-- 共通hidden --}}
  @include('admin.process.partials.edit1.common')
  <div>
    {{ $item->title }}
  </div>
  {{-- 規格 --}}
  <div class="text-bold">
    <div class="input-group">
      @if($item->standard_is_num)
        {{-- 単位体積重量：セメント（袋）/ セメント（バラ）/ 砂（細骨材） --}}
        @php($f_name = 'standard_name')
        @php($name = 'items[' . $num . '][' . $f_name . ']')
        @php($value = $item->values[$f_name])
        @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-' . $f_name]))
        <div class="input-group">
          {{ html()->number($name, $value, 0, null, 0.01)->class($class2)->placeholder($item->num_text) }}
          <div class="input-group-append">
            <span class="input-group-text">kg</span>
          </div>
        </div>
      @elseif(!$item->values['is_custom'] && $item->standards->isNotEmpty())
        @php($f_name = 'standard_id')
        @php($name = 'items[' . $num . '][' . $f_name . ']')
        @if(!$item->values['is_custom'] && $item->id==14)
          {{-- フリーフォームの場合 --}}
          @php($value = $item->values[$f_name] ?? $item->values['default'])
          @php($array = $item->standards->pluck('title', 'id')->toArray())
        @else
          {{-- その他 --}}
          @php($value = $item->values[$f_name] ?? $item->values['default'])
          @php($array = $item->standards->pluck('title', 'id')->toArray())
        @endif
        {{ html()->select($name, $array, $value)->placeholder(__('common.empty'))->class($class)->disabled(in_array($f_name, $disables)) }}
      @else
        @php($f_name = 'standard_name')
        @php($name = 'items[' . $num . '][' . $f_name . ']')
        @php($value = $item->values[$f_name])
        {{ html()->input('text', $name, $value)->class($class)->disabled(in_array($f_name, $disables)) }}
      @endif
    </div>
  </div>
  {{-- 数量 --}}
  <div>
    @php($f_name = 'num')
    @php($num_is_output = $item->num_is_output ?? 0)
    @if($num_is_output)
      <div class="input-group c-output">
        @php($class2 = array_merge($class, ['text-right', 'js-calculate-' . $f_name . '-output']))
        <div class="{{ implode(' ', $class2) }}"></div>
        <div class="input-group-append">
          <span class="input-group-text">{!! $item->unitHtml ?? '-' !!}</span>
        </div>
      </div>
    @else
      @php($name = 'items[' . $num . '][' . $f_name . ']')
      @php($value = $item->values[$f_name])
      @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-' . $f_name]))
      {{--
      @php($step = $item->values['is_float'] ? 0.01 : 1)
      --}}
      @php($step = 0.01)
      <div class="input-group">
        {{ html()->number($name, $value, 0, null, $step)->class($class2)->placeholder($item->num_text)->disabled(in_array($f_name, $disables)) }}
        @if(!in_array($f_name, $disables))
          @php($_class = $item->is_multiple_unit ? ' js-dropdown' : null)
          <div class="input-group-append{{ $_class }}">
            @if($item->is_multiple_unit)
              {{-- expense_item_id：1 仮設材他（親綱・アンカー）のみで使用 --}}
              @include('admin.process.partials.edit1.multiple_unit')
            @else
            <span class="input-group-text">{!! $item->unitHtml ?? '-' !!}</span>
            @endif
          </div>
        @endif
      </div>
    @endif
  </div>
  {{-- 単価 --}}
  <div>
    @php($f_name = 'price')
    @php($name = 'items[' . $num . '][' . $f_name . ']')
    @php($value = $item->values[$f_name])
    @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-' . $f_name]))
    <div class="input-group">
      {{ html()->number($name, $value, 0)->class($class2)->disabled(in_array($f_name, $disables)) }}
      @if(!in_array($f_name, $disables))
        <div class="input-group-append">
          <span class="input-group-text">円</span>
        </div>
      @endif
    </div>
  </div>
  {{-- ロス率 --}}
  <div>
    @php($f_name = 'rate')
    @php($name = 'items[' . $num . '][' . $f_name . ']')
    @php($value = $item->values[$f_name])
    @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-' . $f_name]))
    <div class="input-group">
      {{ html()->number($name, $value, 0, null, 0.01)->class($class2)->disabled(in_array($f_name, $disables)) }}
    </div>
  </div>
  {{-- 予算 --}}
  <div>
    <div class="input-group c-output">
      @php($class2 = array_merge($class, ['text-right', 'js-calculate-result']))
      <div class="{{ implode(' ', $class2) }}"></div>
      <div class="input-group-append">
        <span class="input-group-text">円</span>
      </div>
    </div>
  </div>
  {{-- 削除 --}}
  <div class="c-grid-trash">
    @if($item->values['is_custom'])
      <span class="btn btn-default js-expense-delete" data-expense-custom-item-id="{{ $item->id }}"><i class="fa-solid fa-trash-can text-danger"></i></span>
    @endif
  </div>
</div>
