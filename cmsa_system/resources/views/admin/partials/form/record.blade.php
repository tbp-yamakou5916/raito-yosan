@php($type = $type ?? null)
@if($form_type=='edit')
  <div class="row">
    @include('admin.partials.forms', [
        'type' => 'formText',
        'name' => 'created_at',
        'trans' => 'admin',
        'value' => $datum->created_at_label,
        'frame' => 'col-3',
    ])
    @if($type != 'process')
      @include('admin.partials.forms', [
          'type' => 'formText',
          'name' => 'created_by',
          'trans' => 'admin',
          'value' => $datum->created_by_label,
          'frame' => 'col-3',
      ])
    @endif
    @include('admin.partials.forms', [
        'type' => 'formText',
        'name' => 'updated_at',
        'trans' => 'admin',
        'value' => $datum->updated_at_label,
        'frame' => 'col-3',
    ])
    @include('admin.partials.forms', [
        'type' => 'formText',
        'name' => 'updated_by',
        'trans' => 'admin',
        'value' => $datum->updated_by_label,
        'frame' => 'col-3',
    ])
  </div>
@endif
