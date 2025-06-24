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

      {{-- カスタム表示フラグ --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'is_custom',
          'trans' => 'admin.master.unit',
          'array' => __('array.is_custom.params'),
          'frame' => 'col-2',
      ])

      {{-- 費用項目 --}}
      @include('admin.partials.forms', [
          'type' => 'text',
          'name' => 'title',
          'trans' => 'admin.master.unit',
          'frame' => 'col-2',
          'is_required' => true,
      ])
    </div>
    {{-- 作成 / 更新記録 --}}
    @include('admin.partials.form.record')
  </div>
</div>
