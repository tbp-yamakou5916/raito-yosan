<div class="card">
  <div class="card-header bg-secondary">
    <span class="card-title">現場条件</span>
  </div>
  <div class="card-body">
    <div class="row">
      @foreach(__('condition') as $_name => $params)
        @php($_label =  __('condition.' . $_name . '.label'))
        @php($_note = null)
        @if(Illuminate\Support\Facades\Lang::has('condition.' . $_name . '.note'))
          @php($_label =  __('condition.' . $_name . '.short'))
          @php($_note = '※' . __('condition.' . $_name . '.note'))
        @endif
        @include('admin.partials.forms', [
            'type' => 'select',
            'name' => $_name,
            'label_text' => '［' . __('condition.' . $_name . '.code') . '］' . $_label ,
            'array' => __('condition.' . $_name . '.params'),
            'frame' => 'col-6',
            'is_required' => true,
            'is_empty' => true,
            'note' => $_note
        ])
      @endforeach
    </div>
  </div>
</div>
