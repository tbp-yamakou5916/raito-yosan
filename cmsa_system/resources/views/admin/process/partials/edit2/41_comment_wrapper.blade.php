<div class="card js-comment-wrapper" data-cost-type="{{ $cost_type }}">
  <div class="card-header bg-secondary d-flex align-items-center">
    <span class="card-title">{{ __('array.cost_type.params.' . $cost_type) }}{{ __('admin.process_term_comment.comment') }}</span>　　※予算との乖離理由として、記録が必要な事項を簡潔に記入
  </div>
  <div class="card-body js-comment-body">
    @php($i=0)
    @foreach($process_term->comments->where('cost_type', $cost_type) as $comment)
      @include('admin.process.partials.edit2.comment', ['cost_type' => $cost_type, 'num' => $i++])
    @endforeach
    @include('admin.process.partials.edit2.comment', ['cost_type' => $cost_type, 'num' => $i++, 'comment' => null])
  </div>
  <div class="card-body pt-0">
    <div class="col-4 offset-4">
      <span class="btn btn-block bg-black text-white js-comment-button">
        <i class="fa-solid fa-circle-plus"></i>
        要因追加
      </span>
      {{ html()->hidden('comment_num', $i)->class('js-comment-num') }}
    </div>
  </div>
</div>
