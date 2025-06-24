@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['csv'],
    ],
])

@section('content')
  {{-- ファイルアップロード --}}
  @include('admin.csv.partials.upload', ['type' => 'csv'])

  <div class="card">
    <div class="card-header bg-secondary">
      <span class="card-title">登録別作業実績</span>
    </div>
    <div class="card-body">
      {{ html()->form('GET')->route('admin.csv.index')->open() }}
      <div class="row">
        <div class="col-3">
          {{ html()->select('date', $dates, $date)->class(['form-control'])->placeholder('（期間選択）') }}
        </div>
        <div class="col-3">
          {{ html()->select('process_type', $processes, $process_type)->class(['form-control'])->placeholder('（' . __('admin.process._menu') . '選択）') }}
        </div>
        <div class="col1">
          {{ html()->submit('絞り込み')->class(['btn', 'btn-warning']) }}
        </div>
      </div>
      {{ html()->form()->close() }}
    </div>
    <div class="card-body p-0">
      <table class="table table-striped table-hover" id="DataTable">
        <thead>
        <tr>
          <td>
            {{ __('admin.process._menu') }}
          </td>
          <td>
            {{ __('admin.process_term.construction_term') }}
          </td>
          <td>
            {{ __('admin.updated_by') }}
          </td>
          <td>
            {{ __('admin.updated_at') }}
          </td>
          <td></td>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $datum)
          <tr>
            <td class="text-nowrap" data-sort="{{ $datum->process_type }}">
              {{ $datum->processLabel }}
            </td>
            <td class="text-nowrap" data-sort="{{ $datum->process_type }}">
              <a href="{{ route('admin.process.edit', ['process' . $datum->process_type, $datum->id]) }}" class="btn btn-info btn-sm">
                {{ $datum->constructionTermLabel }}
              </a>
            </td>
            <td>
              {{ $datum->createdByLabel }}
            </td>
            @if($datum->created_at == $datum->updated_at)
              <td>
                {{ $datum->createdAtLabel }}
              </td>
              <td>
                {{ $datum->updatedByLabel }}
              </td>
            @else
              <td>
                {{ $datum->updatedAtLabel }}
              </td>
              <td class="text-nowrap">
                {!! $datum->btnDestroy !!}
              </td>
            @endif
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
  <script crossorigin src="{{ asset('assets/scripts/csv.js') }}"></script>
@stop
