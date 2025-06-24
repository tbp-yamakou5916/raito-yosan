<div class="card">
  <div class="card-body">
    <div class="row">
      {{-- 有効フラグ --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'invalid',
          'trans' => 'admin',
          'array' => __('array.invalid.params'),
          'frame' => 'col-2',
      ])

      <div class="col-10"></div>

      {{-- 拠点 --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'location_id',
          'trans' => 'admin.user.user',
          'frame' => 'col-4',
          'is_empty' => true,
          'is_required' => true,
          'array'=> $locations,
      ])

      {{-- 権限 --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'roles[]',
          'label' => 'admin.user.user.roles',
          'frame' => 'col-4',
          'is_empty' => true,
          'is_required' => true,
          'value' => $form_type=='edit' ? $datum->roles->pluck('id') : Null,
          'array'=> $roles,
      ])

      <div class="col-4"></div>

      {{-- 名前 --}}
      @include('admin.partials.forms', [
          'type' => 'text',
          'name' => 'name',
          'trans' => 'admin.user.user',
          'frame' => 'col-4',
          'is_required' => true,
          'placeholder' => '雷都 太郎'
      ])

      {{-- Email --}}
      @include('admin.partials.forms', [
          'type' => 'email',
          'name' => 'email',
          'trans' => 'admin.user.user',
          'frame' => 'col-4',
          'is_required' => true,
          'placeholder' => 'raito@raito-yosan.com'
      ])

      {{-- パスワード --}}
      @include('admin.partials.forms', [
          'type' => 'password',
          'name' => 'password',
          'trans' => 'admin.user.user',
          'frame' => 'col-4',
          'is_required' => $form_type=='create',
          'plus_params' => [
            'autocomplete' => 'new-password',
          ],
      ])
    </div>
    {{-- 作成 / 更新記録 --}}
    @include('admin.partials.form.record')
  </div>
</div>

