@php($class = ['text-right', 'form-control'])
<div class="c-grid c-grid--outsourcing js-expense-row js-calculate-wrapper"
     data-type="outsourcing"
     data-process-id="{{ $item->values['process_id'] }}"
     data-item-id="{{ $item->id }}"
     data-is-custom="{{ (int) $item->values['is_custom'] }}"
     data-num="{{ $num }}"
>

  {{-- 共通hidden --}}
  @include('admin.process.partials.edit1.common')
  <div>
    {{ $item->title }}
  </div>
  {{-- IN11：数量 --}}
  <div>
    @php($f_name = 'num')
    @php($name = 'items[' . $num . '][' . $f_name . ']')
    @php($value = $item->values[$f_name])
    @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-' . $f_name]))
    <div class="input-group">
      {{ html()->number($name, $value, 0, null, 0.01)->class($class2) }}
      <div class="input-group-append">
        <span class="input-group-text">{!! $item->unitHtml ?? '-' !!}</span>
      </div>
    </div>
  </div>
  {{-- OUT11：単価 --}}
  <div>
    @php($f_name = 'price')
    @php($name = 'items[' . $num . '][' . $f_name . ']')
    @php($value = $item->values[$f_name])
    @php($class2 = array_merge($class, ['js-calculate', 'js-calculate-' . $f_name]))
    <div class="input-group">
      {{ html()->number($name, $value, 0)->class($class2) }}
      <div class="input-group-append">
        <span class="input-group-text">円</span>
      </div>
    </div>
  </div>
  {{-- OUT12：予算金額 --}}
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

