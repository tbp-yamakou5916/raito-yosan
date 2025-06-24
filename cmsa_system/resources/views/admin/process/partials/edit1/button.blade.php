<div class="row  pt-2 pb-4">
  {{ html()->hidden('next_process_type', 0) }}
  <div class="col-6 offset-3">
    {{ html()->button(__('common.update'), 'submit')->class(['btn', 'btn-info', 'btn-block']) }}
  </div>
  @if(!in_array($process_type, ['all', 7]))
    <div class="col-3">
      @php($label = __('array.process_type.params.' . ($process_type + 1)) . 'へ')
      {{ html()->button($label, 'submit')->class(['btn', 'btn-warning', 'btn-block', 'js-next-process'])->attribute('data-next-process', $process_type + 1) }}
    </div>
  @endif
</div>
