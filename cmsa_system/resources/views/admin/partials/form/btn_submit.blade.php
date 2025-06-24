@php($type = $type ?? null)
<div class="col-6 offset-3 pt-2 pb-4">
  @if($form_type=='create')
    @php($label = $type == 'delivery' ? __('common.create2') : __('common.create'))
    {{ html()->button($label, 'submit')->class(['btn', 'btn-success', 'btn-block', 'js-submit']) }}
  @else
    {{ html()->button(__('common.update'), 'submit')->class(['btn', 'btn-info', 'btn-block', 'js-submit']) }}
  @endif
</div>
