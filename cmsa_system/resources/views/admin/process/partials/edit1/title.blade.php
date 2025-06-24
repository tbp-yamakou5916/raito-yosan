@if(!$item->values['is_custom'] && $item->process_type != $item_process_type)
  <div class="c-grid-title">
    {{ $item->processTypeLabel }}
  </div>
  @php($item_process_type = $item->process_type)
@endif
