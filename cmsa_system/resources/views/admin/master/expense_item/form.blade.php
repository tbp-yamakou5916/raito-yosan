<div class="card">
  <div class="card-body">
    <div class="row">
      {{-- 有効フラグ --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'invalid',
          'label' => 'array.invalid.label',
          'array' => __('array.invalid.params'),
          'frame' => 'col-2',
      ])

      {{-- 並び順 --}}
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => 'sequence',
          'trans' => 'admin',
          'frame' => 'col-2',
          'is_required' => true,
          'value' => $datum->sequence ?? $sequence ?? null,
          'pattern' => '[0-9]',
          'placeholder' => '半角数字',
      ])

      <div class="col-8"></div>

      {{-- 工程タイプ --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'process_type',
          'label' => 'array.process_type.label',
          'array' => __('array.process_type.params'),
          'frame' => 'col-4',
          'is_required' => true,
          'is_empty' => true,
      ])

      {{-- 費用タイプ --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'cost_type',
          'label' => 'array.cost_type.label',
          'array' => __('array.cost_type.params'),
          'frame' => 'col-4',
          'class' => 'js-cost-type',
          'is_required' => true,
          'is_empty' => true,
      ])

      {{-- 単位 --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'unit_id',
          'label' => 'admin.master.unit.title',
          'array' => $units,
          'frame' => 'col-4',
          'is_empty' => true,
      ])

      {{-- 費用項目名 --}}
      @include('admin.partials.forms', [
          'type' => 'text',
          'name' => 'title',
          'trans' => 'admin.master.expense_item',
          'frame' => 'col-12',
          'is_required' => true,
      ])

      {{-- 費用項目名 --}}
      {{--
      @include('admin.partials.forms', [
          'type' => 'text',
          'name' => 'num_text',
          'trans' => 'admin.master.expense_item',
          'frame' => 'col-4',
          'note' => '「数量」のプレースホルダーで表示します',
      ])
      --}}

      {{-- 材料費 / 外注費［デ］数量 --}}
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => 'default_num',
          'trans' => 'admin.master.expense_item',
          'frame' => 'col-4',
      ])

      {{-- 材料費［デ］ロス率 --}}
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => 'default_rate',
          'trans' => 'admin.master.expense_item',
          'frame' => 'col-4 js-material',
          'step' => 0.01,
      ])

      {{-- 外注費［デ］作業割合/日 --}}
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => 'default_rate2',
          'trans' => 'admin.master.expense_item',
          'frame' => 'col-4 js-outsourcing',
          'step' => 0.01,
      ])

      {{-- 外注費［デ］想定人工 --}}
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => 'default_popularity',
          'trans' => 'admin.master.expense_item',
          'frame' => 'col-4 js-outsourcing',
          'step' => 0.01,
      ])
    </div>
    {{-- 作成 / 更新記録 --}}
    @include('admin.partials.form.record')
  </div>
</div>

@section('js')
  @parent
  <script crossorigin src="{{ asset('assets/scripts/expense_item.js') }}"></script>
@endsection
