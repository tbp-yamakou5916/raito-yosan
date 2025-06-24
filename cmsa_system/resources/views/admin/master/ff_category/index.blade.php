@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['master'],
      ['master.ff_category'],
    ],
])

@section('content')
  <div class="card">
    <div class="card-body p-0">
      <table class="table table-striped table-hover" id="DataTable">
        <thead>
        <tr>
          <td>
            {{ __('admin.master.ff_category.title') }}
          </td>
          <td>
            {{ __('admin.master.ff_category.width.label') }}
          </td>
          <td>
            {{ __('admin.master.ff_category.length.label') }}
          </td>
          <td>
            {{ __('admin.master.ff_category.frame_width.label') }}
          </td>
          <td>
            {{ __('admin.master.ff_category.area.label') }}
          </td>
          <td>
            {{ __('admin.master.ff_category.frame.label') }}
          </td>
          <td>
            {{ __('admin.master.ff_category.main_anchor.label') }}
          </td>
          <td>
            {{ __('admin.master.ff_category.sub_anchor.label') }}
          </td>
          <td>
            {{ __('admin.master.ff_category.rebar.label') }}
          </td>
          <td>
            {{ __('admin.master.ff_category.rebar_spec') }}
          </td>
          <td>
            {{ __('admin.master.ff_category.stirrup.label') }}
          </td>
          <td>
            {{ __('admin.master.ff_category.stirrup_spec') }}
          </td>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $datum)
          <tr class="{{ $datum->tr_class }}">
            <td>
              {{ $datum->title }}
            </td>
            <td>
              {{ $datum->widthLabel }}
            </td>
            <td>
              {{ $datum->lengthLabel }}
            </td>
            <td>
              {{ $datum->frameWidthLabel }}
            </td>
            <td>
              {{ $datum->areaLabel }}
            </td>
            <td>
              {{ $datum->frameLabel }}
            </td>
            <td>
              {{ $datum->mainAnchorLabel }}
            </td>
            <td>
              {{ $datum->subAnchorLabel }}
            </td>
            <td>
              {{ $datum->rebarLabel }}
            </td>
            <td>
              {{ $datum->rebar_spec }}
            </td>
            <td>
              {{ $datum->stirrupLabel }}
            </td>
            <td>
              {{ $datum->stirrup_spec }}
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
