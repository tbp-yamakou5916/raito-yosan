{{-- フラッシュメッセージ --}}
@php($is_show = false)
@if(session('msg_success') || session('status'))
    @php($is_show = true)
    @php($type = 'success')
    @php($icon = 'fas fa-thumbs-up')
    @php($title = 'Success!')
    @if(session('msg_success'))
        @php($message = nl2br(session('msg_success')))
    @else
        @php($message = nl2br(session('status')))
    @endif
@elseif(session('msg_err') || $errors->any())
    @php($is_show = true)
    @php($type = 'danger')
    @php($icon = 'fas fa-exclamation-triangle')
    @php($title = 'Alert!')
    @if(session('msg_err'))
        @php($message = nl2br(session('msg_err')))
    @else
        @php($message = '<ul>')
        @foreach ($errors->all() as $error)
            @php($message .= '<li>' . $error . '</li>')
        @endforeach
        @php($message .= '</ul>')
    @endif
@endif

@if($is_show)
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-{{ $type }}">
                    <span class="card-title">
                        <i class="{{ $icon }}"></i>
                        {{ $title }}
                    </span>
                </div>
                <div class="card-body">
                    {!! $message !!}
                </div>
            </div>
        </div>
    </div>
@endif
