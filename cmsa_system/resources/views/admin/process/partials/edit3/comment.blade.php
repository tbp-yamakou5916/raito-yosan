<div class="card">
  <div class="card-header bg-secondary">
    <span class="card-title">{{ __('array.cost_type.params.' . $cost_type) }}コメント</span>
  </div>
  <div class="card-body p-0">
    <table class="table table-bordered table-hover">
      <colgroup>
        <col class="col-2">
        <col>
        <col class="col-2">
      </colgroup>
      <thead>
      <tr>
        {{-- 施工期間 --}}
        <th>
          {{ __('admin.process.base.edit2.construction_term') }}
        </th>
        {{-- 要因区分 / コメント --}}
        <th>
          {{ __('admin.process_term_comment.condition_key') }} /
          {{ __('admin.process_term_comment.comment') }}
        </th>
        <th>
          {{ __('admin.updated_at') }}
        </th>
      </tr>
      </thead>
      <tbody>
      @php($last_term = null)
      @foreach($comments->where('cost_type', $cost_type) as $comment)
        <tr>
          <td class="text-nowrap">
            @if($last_term != $comment->construction_term_label)
              <a href="{{ route('admin.process.change', ['process' . $comment->process_type, $comment->process_term_id]) }}" class="btn btn-info btn-sm">
                {{ $comment->construction_term_label }}
              </a>
            @endif
            @php($last_term = $comment->construction_term_label)
          </td>
          <td>
            @if($comment->conditionLabel)
              <span class="text-bold">{{ $comment->conditionLabel }}</span><br>
            @endif
            {{ $comment->comment }}
          </td>
          <td class="text-nowrap">
            @if($comment->updated_at == $comment->created_at)
              {!! $comment->createdHtml !!}
            @else
              {!! $comment->updatedHtml !!}
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
