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

      {{-- 工事コード --}}
      @include('admin.partials.forms', [
          'type' => 'number',
          'name' => 'code',
          'trans' => 'admin.project',
          'frame' => 'col-4',
          'is_required' => true,
      ])

      {{-- 拠点名 --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'location_id',
          'label' => 'admin.master.location.title',
          'array' => $locations,
          'frame' => 'col-4',
          'is_required' => true,
          'is_empty' => true,
      ])
      @php($location_id = $datum->location_id ?? null)

      {{-- 現場略名 --}}
      @include('admin.partials.forms', [
          'type' => 'text',
          'name' => 'title',
          'trans' => 'admin.project',
          'frame' => 'col-12',
          'is_required' => true,
      ])

      {{-- 現場工事長 --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'user_id',
          'trans' => 'admin.project',
          'array' => [],
          'frame' => 'col-3',
          'class' => 'js-user js-manager',
          'is_required' => true,
          'is_empty' => true,
          'is_disabled' => !$location_id || $form_type == 'create',
      ])

      {{-- 現場ユーザー1 --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'field_user1_id',
          'trans' => 'admin.project',
          'array' => [],
          'frame' => 'col-3',
          'class' => 'js-user js-field-user1',
          'is_empty' => true,
          'is_disabled' => !$location_id || $form_type == 'create',
      ])

      {{-- 現場ユーザー2 --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'field_user2_id',
          'trans' => 'admin.project',
          'array' => [],
          'frame' => 'col-3',
          'class' => 'js-user js-field-user2',
          'is_empty' => true,
          'is_disabled' => !$location_id || $form_type == 'create',
      ])

      {{-- 現場ユーザー3 --}}
      @include('admin.partials.forms', [
          'type' => 'select',
          'name' => 'field_user3_id',
          'trans' => 'admin.project',
          'array' => [],
          'frame' => 'col-3',
          'class' => 'js-user js-field-user3',
          'is_empty' => true,
          'is_disabled' => !$location_id || $form_type == 'create',
      ])

      <div class="col-4"></div>
    </div>
    {{-- 作成 / 更新記録 --}}
    @include('admin.partials.form.record')
  </div>
</div>

@section('js')
  @parent
  <script>
    const projectParams = {
      fetchRoute: '{{ route('admin.project.api.getUser') }}',
      formType: '{{ $form_type }}',
      user_id: {{ $datum->user_id ?? 0 }},
      field_user1_id: {{ $datum->field_user1_id ?? 0 }},
      field_user2_id: {{ $datum->field_user2_id ?? 0 }},
      field_user3_id: {{ $datum->field_user3_id ?? 0 }},
    }
  </script>
  <script crossorigin src="{{ asset('assets/scripts/project.js') }}"></script>
@endsection
