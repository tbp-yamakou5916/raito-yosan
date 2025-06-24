@extends('adminlte::page')
@php($is_error = $is_error ?? false)

@section('adminlte_css')
  <script src="https://kit.fontawesome.com/bc63c2689a.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@stop

@php($classes_bodies = ['mode' . $mode_num, 'layout-fixed', 'sidebar-mini'])
@if($now_project)
  @php($classes_bodies[] = 'is-project')
@endif
@if($now_free_form)
  @php($classes_bodies[] = 'is-free-form')
@endif
@section('classes_body', implode(' ', $classes_bodies))

@section('content_header')
  {{-- アラート --}}
  @include('admin.layouts.partials.notification')

  @if(!($route_name == 'admin.process.edit' && $mode_num == 3))
    <div class="row mb-2">
      {{-- タイトル --}}
      <div class="col-6">
        <h1>@yield('h1_title')</h1>
      </div>
      {{-- パンくず --}}
      {{--
      <div class="col-6">
        <ol class="breadcrumb float-sm-right">
          @yield('breadcrumb')
        </ol>
      </div>
      --}}
    </div>
  @endif
@stop

@section('footer')
  <div class="text-center">
    <p>
      Copyright <strong> &copy;</strong> Raito Industry CO.,LTD. All Rights Reserved. / Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
    </p>
  </div>
@endsection

{{-- DataTabel --}}
@include('admin.layouts.partials.datatable')

@section('js')
  @parent
  <script>
    window._token = $('meta[name="csrf-token"]').attr('content');
    const baseParams = {
      fetchRoute: '{{ route('admin.api.modeChange') }}',
    }
  </script>
  <script crossorigin src="{{ asset('assets/scripts/base.js') }}"></script>
@endsection


