@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['chart'],
    ],
])

@section('content')
  <div class="card">
    <div class="card-body">
      <div class="c-chart">
        <div class="c-chart-left">
          <div class="c-chart-left-main js-chart-left">
            @foreach($terms as $key => $params)
              @continue($key=='all')
              <div class="c-chart-left-main-item">
                <span class="c-chart-left-main-item--title">{{ $params['label'] }}</span><br>
                <span class="c-chart-left-main-item--plan">{{ $params['plan']['term_label'] }}</span>（{{ $params['plan']['day_label'] }}）<br>
                <span class="c-chart-left-main-item--result">{{ $params['result']['term_label'] }}（{{ $params['result']['day_label'] }}）</span>
              </div>
            @endforeach
          </div>
          <div class="c-chart-footer">
            <div class="c-chart-footer-item">
              計画出来高<br>
              実施出来高
            </div>
            <div class="c-chart-footer-item">
              計画予算<br>
              実施予算
            </div>
          </div>
        </div>
        <div class="c-chart-main">
          <div class="c-chart-main-graph">
            <canvas id="ganttCanvas" class="c-chart-main-graph-item c-chart-main-graph-item--gantt" width="{{ $canvas['width'] }}"></canvas>
            <canvas id="lineCanvas" class="c-chart-main-graph-item c-chart-main-graph-item--line" width="{{ $canvas['width'] }}"></canvas>
          </div>
          <div class="c-chart-main-footer js-chart-foot" style="grid-template-columns: repeat({{ $canvas['num'] }}, 1fr);">
            @foreach($footer as $params)
              <div class="c-chart-footer --main">
                <div class="c-chart-footer-item">
                  {{ $params['plan']['rate_label'] }}<br>
                  {{ $params['result']['rate_label'] }}
                </div>
                <div class="c-chart-footer-item">
                  {{ $params['plan']['step_total_label'] }}<br>
                  {{ $params['result']['step_total_label'] }}
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  @parent
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const chartParams = {
      labels: @json($labels),
      datasets: @json($datasets),
      options: @json($options),
      tasks: @json($tasks),
    }
  </script>
  <script crossorigin src="{{ asset('assets/scripts/chart.js') }}"></script>
@endsection

