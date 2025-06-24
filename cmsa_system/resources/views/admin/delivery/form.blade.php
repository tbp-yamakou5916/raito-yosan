<div class="card">
  <div class="card-body">
    <div class="row">
      {{-- 資材搬入日 --}}
      @include('admin.partials.forms', [
          'type' => 'date',
          'name' => 'delivered_at',
          'trans' => 'admin.delivery',
          'frame' => 'col-3',
          'is_required' => true,
      ])
      {{-- 資材名 --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'process_item_id',
          'label' => 'admin.delivery.name',
          'array' => $selects,
          'frame' => 'col-6',
          'is_empty' => !$view_type,
          'is_required' => true,
      ])
      {{-- 搬入数量 --}}
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => 'num',
          'trans' => 'admin.delivery',
          'frame' => 'col-3',
          'is_required' => true,
          'append' => '-',
          'min' => 0,
          'step' => 0.01,
      ])
    </div>
    {{-- 作成 / 更新記録 --}}
    @include('admin.partials.form.record')
  </div>
</div>

@if($view_type)
  <div class="card">
    <div class="card-header bg-secondary">
      <span class="card-title">搬入履歴</span>
    </div>
    <div class="card-body p-0">
      @include('admin.delivery.partials.table', [
        'view_type' => 0,
        'data' =>  $deliveries
      ])
    </div>
  </div>
@endif

@section('js')
  @parent
  <script>
    const deliveryParams = {
      fetchRoute: '{{ route('admin.delivery.api.getUnit') }}',
    }
  </script>
  <script crossorigin src="{{ asset('assets/scripts/delivery.js') }}"></script>
@endsection
