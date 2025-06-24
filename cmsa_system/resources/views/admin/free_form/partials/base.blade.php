<div class="card">
  <div class="card-header bg-secondary">
    <span class="card-title">基本情報</span>
  </div>
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

      {{-- 区分ラベル --}}
      @include('admin.partials.forms', [
        'type' => 'text',
        'name' => 'title',
        'trans' => 'admin.free_form',
        'frame' => 'col-6',
        'is_required' => true,
      ])

      {{-- 工種 --}}
      @include('admin.partials.forms', [
        'type' => 'select',
        'name' => 'construction_type',
        'trans' => 'admin.free_form',
        'frame' => 'col-6',
        'array' => __('array.construction_type'),
        'is_required' => true,
      ])

      {{-- 法枠タイプ --}}
      <div class="form-group col-6">
        <span class="badge badge-danger required_label">必須</span>
        <label class="col-form-label" for="ff_category_id">
          {{ __('admin.master.ff_category._menu') }}
        </label>
        @php($_value = $datum->ff_category_id ?? null)
        <select class="form-control js-calculate" name="ff_category_id" id="ff_category_id" required="required">
          <option value="">（選択）</option>
          @foreach($ff_categories as $params)
            @php($selected = $_value == $params['id'] ? ' selected' : null)
            <option value="{{ $params['id'] }}" data-type="{{ $params['frame_width_type'] }}"{{ $selected }}>{{ $params['label'] }}</option>
          @endforeach
        </select>
      </div>
    </div>
    {{-- 作成 / 更新記録 --}}
    @include('admin.partials.form.record')
  </div>
</div>
