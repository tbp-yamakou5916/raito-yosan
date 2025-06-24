@php($class = ['form-control'])
{{ html()->hidden('comment[' . $cost_type . '][' . $num . '][id]', $comment->id ?? null) }}
<div class="row mb-2">
  <div class="col-3">
    {{ html()->select('comment[' . $cost_type . '][' . $num . '][condition_key]', $conditions, $comment->condition_key ?? null)->class($class)->placeholder(__('admin.process_term_comment.condition_key')) }}
  </div>
  <div class="col-9">
    {{ html()->text('comment[' . $cost_type . '][' . $num . '][comment]', $comment->comment ?? null)->class($class)->placeholder(__('admin.process_term_comment.comment') . 'を入力') }}
  </div>
</div>
