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

      <div class="col-10"></div>

      {{-- FFカテゴリ --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'ff_category_id',
          'label' => 'admin.master.ff_category._menu',
          'array' => $ff_categories,
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
      fetchRoute: '{{ route('admin.master.ff_default.api.getStandard') }}',
      standardId: {{ $datum->standard_id ?? 0 }},
      formType: '{{ $form_type }}',
      defaults: @json($defaults ?? [])
    }
  </script>
  <script crossorigin src="{{ asset('assets/scripts/ff_default.js') }}"></script>
@endsection
