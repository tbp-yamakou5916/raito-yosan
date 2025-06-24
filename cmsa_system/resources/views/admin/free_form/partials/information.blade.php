@php($is_test = config('calculator.process'))
@php($_label = $is_test ? 'long' : 'label')
<div class="card">
  <div class="card-header bg-secondary">
    <span class="card-title">施工情報</span>
  </div>
  <div class="card-body">
    <div class="row">

      {{-- ① 面積 --}}
      @php($_name = 'area')
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => $_name,
          'min' => 0,
          'step' => 0.001,
          'label' => 'admin.free_form.' . $_name . '.' . $_label,
          'frame' => 'col-4',
          'is_required' => true,
          'append' => __('admin.free_form.' . $_name . '.unit'),
          'class' => 'js-calculate',
      ])

      {{-- ② 外注基本単価 --}}
      @php($_name = 'price')
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => $_name,
          'min' => 0,
          'label' => 'admin.free_form.' . $_name . '.' . $_label,
          'frame' => 'col-4',
          'is_required' => true,
          'append' => __('admin.free_form.' . $_name . '.unit'),
          'class' => 'js-calculate',
      ])

      {{-- ③ 枠内吹付厚さ --}}
      @php($_name = 'thickness')
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => $_name,
          'min' => 0,
          'step' => 0.001,
          'label' => 'admin.free_form.' . $_name . '.' . $_label,
          'frame' => 'col-4',
          'is_required' => true,
          'append' => __('admin.free_form.' . $_name . '.unit'),
          'class' => 'js-calculate',
      ])

      {{-- ㉑ 法枠工面積 --}}
      {{--
      @php($_name = 'frame_area')
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => $_name,
          'min' => 0,
          'step' => 0.001,
          'label' => 'admin.free_form.' . $_name . '.' . $_label,
          'frame' => 'col-4',
          'is_disabled' => true,
          'append' => __('admin.free_form.' . $_name . '.unit'),
          'class' => 'js-frame-extend',
          'note' => $is_test ? __('admin.master.ff_category._menu') : null,
      ])
      --}}

      {{-- ⑫ 法枠幅 --}}
      @php($_name = 'frame_width')
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => $_name,
          'min' => 0,
          'step' => 0.001,
          'label' => 'admin.free_form.' . $_name . '.' . $_label,
          'frame' => 'col-4',
          'is_disabled' => true,
          'append' => __('admin.free_form.' . $_name . '.unit'),
          'class' => 'js-frame-extend',
          'note' => $is_test ? __('admin.master.ff_category._menu') : null,
      ])

      {{-- ⑬ 対象数量 --}}
      @php($_name = 'quantity')
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => $_name,
          'min' => 0,
          'step' => 0.001,
          'label' => 'admin.free_form.' . $_name . '.' . $_label,
          'frame' => 'col-4',
          'append' => __('admin.free_form.' . $_name . '.unit'),
          'class' => 'js-frame-extend',
          'note' => $is_test ? __('admin.master.ff_category._menu') . ' + ' . __('admin.free_form.area.long') : null
      ])

      {{-- ⑭ 法枠数 --}}
      {{--
      @php($_name = 'frame_num')
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => $_name,
          'min' => 0,
          'step' => 0.001,
          'label' => 'admin.free_form.' . $_name . '.' . $_label,
          'frame' => 'col-4',
          'is_disabled' => true,
          'append' => __('admin.free_form.' . $_name . '.unit'),
          'note' => $is_test ? __('admin.master.ff_category._menu') . ' + ' . __('admin.free_form.area.long') : null
      ])
      --}}

      {{-- ⑮ 1枠の枠内面積 --}}
      @php($_name = 'one_frame_inner_area')
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => $_name,
          'min' => 0,
          'step' => 0.001,
          'label' => 'admin.free_form.' . $_name . '.' . $_label,
          'frame' => 'col-4',
          'is_disabled' => true,
          'append' => __('admin.free_form.' . $_name . '.unit'),
          'class' => 'js-frame-extend',
          'note' => $is_test ? __('admin.master.ff_category._menu') : null,
      ])

      {{-- ⑯ 枠内面積 --}}
      @php($_name = 'frame_inner_area')
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => $_name,
          'min' => 0,
          'step' => 0.001,
          'label' => 'admin.free_form.' . $_name . '.' . $_label,
          'frame' => 'col-4',
          'append' => __('admin.free_form.' . $_name . '.unit'),
          'note' => $is_test ? __('admin.master.ff_category._menu') . ' + ' . __('admin.free_form.area.long') : null,
      ])

    </div>
  </div>
</div>
