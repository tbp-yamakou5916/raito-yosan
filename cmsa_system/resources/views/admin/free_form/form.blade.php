{{-- 基本情報 --}}
@include('admin.free_form.partials.base')

{{-- 施工情報 --}}
@include('admin.free_form.partials.information')

{{-- 現場条件 --}}
@include('admin.free_form.partials.condition')

@section('js')
  @parent
  <script>
    const freeformParams = {
      fetchRoute: '{{ route('admin.free_form.api.getResult') }}',
    }
  </script>
  <script crossorigin src="{{ asset('assets/scripts/free_form.js') }}"></script>
  {{-- 入力チェックアラート --}}
  <script crossorigin src="{{ asset('assets/scripts/check_input.js') }}"></script>
@endsection
