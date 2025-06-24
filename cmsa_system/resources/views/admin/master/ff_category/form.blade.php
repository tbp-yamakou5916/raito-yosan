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

      {{-- フリーフォームカテゴリ --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'ff_category_id',
          'label' => 'master.free_form_category._menu',
          'array' => $free_form_type,
          'frame' => 'col-4',
          'is_required' => true,
          'is_empty' => true,
      ])

      {{-- 費用項目名 --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'expense_item_id',
          'label' => 'admin.master.expense_item.title',
          'array' => $expense_items,
          'frame' => 'col-4',
          'is_required' => true,
          'is_empty' => true,
      ])

      {{-- 規格名 --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'standard_id',
          'label' => 'admin.master.standard.title',
          'array' => [],
          'frame' => 'col-4',
          'is_required' => true,
          'is_empty' => true,
          'is_disabled' => true,
      ])
    </div>
    {{-- 作成 / 更新記録 --}}
    @include('admin.partials.form.record')
  </div>
</div>

@section('js')
  @parent
  <script>
    const scriptParams = {
      fetchRoute: '{{ route('admin.master.ff_category.api.getStandard') }}',
      standardId: {{ $datum->standard_id ?? 0 }},
    }
  </script>
  <script crossorigin src="{{ asset('assets/scripts/ff_category.js') }}"></script>
@endsection
