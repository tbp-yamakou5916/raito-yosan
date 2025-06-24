<div class="card">
  <div class="card-header bg-secondary">
    <span class="card-title">登録済み{{ __('admin.process_term._menu') }}</span>
  </div>
  <div class="card-body">
      @if($process_terms->isEmpty())
      <div class="row">
        <p>登録済みの{{ __('admin.process_term._menu') }}はありません</p>
      </div>
      @else
        @foreach($process_terms as $term)
          <div class="row mt-2">
            <div class="col-2 text-right">
              施工期間：
            </div>
            <div class="col-3 text-left">
              {{ $term->constructionTermLabel }}
            </div>
            <div class="col-2 text-right">
              登録日（更新日）：
            </div>
            <div class="col-3 text-left">
              {{ $term->createdAtLabel }}
            </div>
            <div class="col-2 text-center">
              @if($process_term->id == $term->id)
                <span class="btn btn-warning btn-sm">選択中</span>
              @else
                <a href="{{ route('admin.process.edit', ['process' . $term->process_type, $term->id]) }}" class="btn btn-info btn-sm">
                  {{ __('common.edit') }}
                </a>
              @endif
            </div>
          </div>
        @endforeach
      @endif
  </div>
</div>
