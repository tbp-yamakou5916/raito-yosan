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

            {{-- 並び順 --}}
            @include('admin.partials.forms', [
                'type' => 'number',
                'name' => 'sequence',
                'trans' => 'admin',
                'frame' => 'col-2',
                'is_required' => true,
                'pattern' => '[0-9]',
                'placeholder' => '半角数字',
            ])

            <div class="col-8"></div>

            {{-- 権限名 --}}
            @include('admin.partials.forms', [
                'type' => 'text',
                'name' => 'name',
                'trans' => 'admin.user.role',
                'frame' => 'col-4',
                'is_required' => true,
            ])

            {{-- 日本語名 --}}
            @include('admin.partials.forms', [
                'type' => 'text',
                'name' => 'ja',
                'trans' => 'admin.user.role',
                'frame' => 'col-4',
                'is_required' => true,
            ])

            {{-- バッジ色 --}}
            @include('admin.partials.forms', [
                'type' => 'select',
                'name' => 'color',
                'trans' => 'admin.user.role',
                'frame' => 'col-4',
                'is_required' => true,
                'array'=> __('array.colors'),
                'is_empty' => true,
            ])

            {{-- パーミッション --}}
            @include('admin.partials.forms', [
                'type' => 'select',
                'name' => 'permissions',
                'label' => 'admin.user.role.permissions',
                'frame' => 'col-4',
                'is_required' => true,
                'array'=> $select['permission'],
                'value' => $form_type=='edit' ? $datum->permissions->pluck('name') : Null,
                'size' => 20,
                'is_multiple' => true,
            ])
        </div>
        {{-- 作成 / 更新記録 --}}
        @include('admin.partials.form.record')
    </div>
</div>
