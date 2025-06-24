@if($type=='csv')
  <div class="card">
    <div class="card-header bg-secondary">
      <span class="card-title">新規作業実績登録</span>
    </div>
    <div class="card-body">
      {{ html()->form()->route('admin.csv.upload')->acceptsFiles()->class('file_upload')->open() }}
      <div class="row">
        @if($type=='csv')
          <div class="col-4">
            @php($options = $options ?? __('array.process_type.params'))
            {{ html()->select('process_type', $options)->class(['form-control'])->placeholder('（' . __('admin.process._menu') . '選択）')->required() }}
          </div>
          {{ html()->hidden('redirect', route('admin.csv.index')) }}
        @else
          {{ html()->hidden('process_type', $process_type) }}
          {{ html()->hidden('redirect', route('admin.process.edit', ['process' . $process_type, $process_term->id])) }}
        @endif
        <div class="col-8">
          <div class="custom-file">
            {{ html()->file('file')->class(['custom-file-input', 'filename'])->accept('.csv')->required() }}
            {{ html()->label('（ファイルを選択）', 'file')->class('custom-file-label') }}
          </div>
        </div>
      </div>
      <div class="row pt-3">
        <div class="col-6 offset-3">
          {{ html()->submit('法面施工管理支援ソフトのインポート')->class(['btn', 'btn-info', 'btn-block']) }}
        </div>
      </div>
      {{ html()->form()->close() }}
    </div>
  </div>
  <div class="card">
    <div class="card-header bg-secondary">
      <span class="card-title">新規作業実績登録（その他工程）</span>
    </div>
    <div class="card-body">
      <div class="row">
        @foreach($buttons as $process_type => $label)
          <div class="col-4">
            <a href="{{ route('admin.csv.create', $process_type) }}" class="btn btn-default btn-block">{{ $label }}</a>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@else
  <div class="card">
    <div class="card-body">
      {{ html()->form()->route('admin.csv.upload')->acceptsFiles()->class('file_upload')->open() }}
      <div class="row">
        @if($type=='csv')
          <div class="col-4">
            {{ html()->select('process_type', __('array.process_type.params'))->class(['form-control'])->placeholder('（' . __('admin.process._menu') . '選択）')->required() }}
          </div>
          {{ html()->hidden('redirect', route('admin.csv.index')) }}
        @else
          {{ html()->hidden('process_type', $process_type) }}
          {{ html()->hidden('redirect', route('admin.process.edit', ['process' . $process_type, $process_term->id])) }}
        @endif
        <div class="col-8">
          <div class="custom-file">
            {{ html()->file('file')->class(['custom-file-input', 'filename'])->accept('.csv')->required() }}
            {{ html()->label('（ファイルを選択）', 'file')->class('custom-file-label') }}
          </div>
        </div>
        @php($class = $type=='process' ? 'col-4' : 'col-6 offset-3 pt-3')
        <div class="{{ $class }}">
          {{ html()->submit('法面施工管理支援ソフトのインポート')->class(['btn', 'btn-info', 'btn-block']) }}
        </div>
      </div>
      {{ html()->form()->close() }}
    </div>
  </div>
@endif


@section('js')
  @parent
  <script crossorigin src="{{ asset('assets/scripts/bs-custom-file-input.min.js') }}"></script>
  <script>
    // ファイルアップロード
    bsCustomFileInput.init();
  </script>
@stop
