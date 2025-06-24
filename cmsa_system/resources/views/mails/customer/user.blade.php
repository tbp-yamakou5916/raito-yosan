{{ $name }} さま

メールアドレス

{{ $email }}

で、ユーザーが作成されました。

下記よりパスワードを設定してください

https://{{ app('url') }}/forgot-password

@include('mails.footer')
