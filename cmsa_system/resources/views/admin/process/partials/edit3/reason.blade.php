<div class="card">
  <div class="card-header bg-secondary">
    <span class="card-title">乖離理由</span>
  </div>
  <div class="card-body js-comment-body">
    @php($reason = $reasons->where('cost_type', $num)->first())
    @php($value = $reason ? $reason->comment : null)
    {{ html()->textarea('reason[' . $num . ']', $value)->class($class)->rows(6) }}
  </div>
</div>
