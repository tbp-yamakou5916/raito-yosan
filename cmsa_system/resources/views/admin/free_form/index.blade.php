@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['free_form'],
    ],
])

@section('content')
  <div class="row mb-4">
    <div class="col-3 offset-9">
      @if($now_project_id)
        <a class="btn btn-success btn-block" href="{{ route('admin.free_form.create') }}">
          {{ __('admin.free_form.title2') }}
          {{ __('common.add') }}
        </a>
      @else
        {{--
        <span class="btn btn-success btn-block disabled">
          {{ __('common.add') }}
        </span>
        --}}
      @endif
    </div>
  </div>

  <div class="card">
    <div class="card-header bg-secondary">
      <span class="card-title">{{ __('admin.free_form._title') }}</span>
    </div>
    <div class="card-body p-0">
      <table class="table table-striped table-hover" id="DataTable">
        <thead>
        <tr>
          <td>
            {{ __('admin.project.title') }}
          </td>
          <td>
            {{ __('admin.free_form.title') }}
          </td>
          <td>
            {{ __('admin.master.ff_category.title') }}
          </td>
          {{--
          <td>
            {{ __('admin.free_form.area.label') }}
          </td>
          <td>
            {{ __('admin.free_form.price.label') }}
          </td>
          <td>
            {{ __('admin.free_form.quantity.label') }}
          </td>
          <td>
            {{ __('admin.free_form.thickness.label') }}
          </td>
          --}}
          <td></td>
          <td></td>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $datum)
          <tr class="{{ $datum->tr_class }}">
            <td>
              {{ $datum->projectTitle }}
            </td>
            <td>
              {{ $datum->title }}
            </td>
            <td>
              {{ $datum->ffCategoryLabel }}
            </td>
            {{--
            <td>
              {{ $datum->area }}
            </td>
            <td>
              {{ $datum->price }}
            </td>
            <td>
              {{ $datum->quantity }}
            </td>
            <td>
              {{ $datum->thickness }}
            </td>
            --}}
            <td class="text-nowrap">
              @if($now_free_form_id==$datum->id)
                <span class="btn btn-default btn-sm disabled">
                  選択中の{{ __('admin.free_form.title2') }}
                </span>
              @else
                <a href="{{ route('admin.process.edit', ['all', 'free_form_id' => $datum->id]) }}" class="btn btn-warning btn-sm">
                  {{ __('admin.free_form.title2') }}選択
                </a>
              @endif
            </td>
            <td class="text-nowrap">
              {!! $datum->btnEdit !!}
              {!! $datum->btnDestroy !!}
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
