@php($class = ['form-control'])
@php($disables = explode(',', $item->disabled))
<div class="c-grid c-grid--outsourcing js-expense-row js-calculate-wrapper"
     data-type="outsourcing"
     data-process-id="{{ $item->values['process_id'] }}"
     data-item-id="{{ $item->id }}"
     data-is-custom="{{ (int) $item->values['is_custom'] }}"
>

  {{-- 共通hidden --}}
  @include('admin.process.partials.edit1.common')
  <div>
    {{ $item->title }}
  </div>
  {{-- IN11：数量 --}}
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
      <div class="input-group">
        {{ html()->number($name, $value, 0, null, 0.01)->class($class2)->placeholder($item->num_text)->disabled(in_array($f_name, $disables)) }}
        @if(!in_array($f_name, $disables))
          <div class="input-group-append">
            <span class="input-group-text">{!! $item->unitHtml ?? '-' !!}</span>
          </div>
        @endif
      </div>
    @endif
  </div>
  {{-- OUT11：単価 --}}
  @php($f_name = 'price')
  <div>
    <div class="input-group c-output">
      @php($class2 = array_merge($class, ['js-calculate-' . $f_name, 'text-right']))
      <div class="{{ implode(' ', $class2) }}"></div>
      <div class="input-group-append">
        <span class="input-group-text">円</span>
      </div>
    </div>
  </div>
  {{-- IN12：想定人工 --}}
  <div>
    @php($f_name = 'popularity')
    @php($name = 'items[' . $num . '][' . $f_name . ']')
    @php($value = $item->values[$f_name])
    @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-' . $f_name]))
    <div class="input-group">
      {{ html()->number($name, $value, 0, null, 0.01)->class($class2)->disabled(in_array($f_name, $disables)) }}
      @if(!in_array($f_name, $disables))
        <div class="input-group-append">
          <span class="input-group-text">{!! $item->unitPopularity !!}</span>
        </div>
      @endif
    </div>
  </div>
  {{-- IN13：作業割合/日 --}}
  <div>
    @php($f_name = 'rate2')
    @php($name = 'items[' . $num . '][' . $f_name . ']')
    @php($value = $item->values[$f_name])
    @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-' . $f_name]))
    <div class="input-group">
      {{ html()->number($name, $value, 0, null, 0.01)->class($class2)->disabled(in_array($f_name, $disables)) }}
    </div>
  </div>
  {{-- IN14：作業人数 --}}
  <div>
    @php($f_name = 'worker_num')
    @php($name = 'items[' . $num . '][' . $f_name . ']')
    @php($value = $item->values[$f_name])
    @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-' . $f_name]))
    <div class="input-group">
      {{ html()->number($name, $value, 0, null, 0.01)->class($class2)->disabled(in_array($f_name, $disables)) }}
      @if(!in_array($f_name, $disables))
        <div class="input-group-append">
          <span class="input-group-text">人</span>
        </div>
      @endif
    </div>
  </div>
  {{-- OUT12：予算 --}}
  <div>
    <div class="input-group c-output">
      @php($class2 = array_merge($class, ['js-calculate-result', 'text-right']))
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

