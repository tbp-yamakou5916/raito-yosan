@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['csv', 'csv.index'],
    ],
    'file' => 'create'
])

@section('content')
  {{ html()->form()->route('admin.csv.store')->open() }}
  {{ html()->hidden('free_form_id', $free_form_id) }}
  {{ html()->hidden('process_type', $process_type) }}
  {{ html()->hidden('created_by', Auth::id()) }}
  <div class="card">
    <div class="card-header bg-secondary">
      <span class="card-title">{{ __('array.process_type.params.' . $process_type) }}</span>
    </div>
    <div class="card-body">
      <div class="row">
        {{-- 施工日 開始 --}}
        @php($_name = 'start')
        @include('admin.partials.forms', [
            'type' => 'date',
            'name' => $_name,
            'trans' => 'admin.process_term',
            'frame' => 'col-4',
            'is_required' => true,
        ])
        {{-- 施工日 終了 --}}
        @php($_name = 'end')
        @include('admin.partials.forms', [
            'type' => 'date',
            'name' => $_name,
            'trans' => 'admin.process_term',
            'frame' => 'col-4',
            'is_required' => true,
        ])
      </div>
    </div>
  </div>


  @include('admin.partials.form.btn_submit', ['form_type' => 'create'])
  {{ html()->form()->close() }}
@endsection
