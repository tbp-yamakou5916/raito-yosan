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


      {{-- 拠点No. --}}
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => 'num',
          'trans' => 'admin.master.location',
          'frame' => 'col-3',
          'is_required' => true,
      ])
      {{-- 拠点名 --}}
      @include('admin.partials.forms', [
          'type' => 'text',
          'name' => 'title',
          'trans' => 'admin.master.location',
          'frame' => 'col-9',
          'is_required' => true,
      ])
    </div>
    {{-- 作成 / 更新記録 --}}
    @include('admin.partials.form.record')
  </div>
</div>
