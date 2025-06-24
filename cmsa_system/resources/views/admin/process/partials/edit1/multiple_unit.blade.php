@php($unit_id = $item->values['unit_id'] ?? 0)
@php($unit_label = '単位')
@foreach($units as $num => $label)
  @if($unit_id == $num)
    @php($unit_label = $label)
    @break
  @endif
@endforeach
<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
  {{ $unit_label }}
</button>
<div class="dropdown-menu c-dropdown-menu" style="">
  <span class="dropdown-item js-dropdown-item" data-num="" data-label="単位">{{ __('common.empty')  }}</span>
  @foreach($units as $num => $label)
    @php($active = $unit_id == $num ? ' is-active' : null)
    <span class="dropdown-item js-dropdown-item{{ $active }}" data-num="{{ $num }}" data-label="{{ $label }}">{{ $label }}</span>
  @endforeach
</div>
{{ html()->hidden('unit_id')->class('js-dropdown-unit') }}
