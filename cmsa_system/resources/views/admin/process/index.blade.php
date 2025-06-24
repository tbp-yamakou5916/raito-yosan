@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['process'],
    ],
])

@section('content')
  <div class="card">
    <div class="card-body p-0">
      <table class="table table-striped table-hover" id="DataTable">
        <thead>
        <tr>
          <td>
            {{ __('admin.process.title') }}
          </td>
          <td></td>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $datum)
          <tr class="{{ $datum->tr_class }}">
            <td>
              {{ $datum->processLabel }}
            </td>
            <td class="text-nowrap">
              <a href="{{ route('admin.process.edit', 'process' . $datum->process_type) }}" class="btn btn-info btn-sm">
                {{ __('common.edit') }}
              </a>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('js')
  @parent
  <script>
    // dataTable設定
    let setting = {};
    $('#DataTable').DataTable(setting);
  </script>
@endsection
