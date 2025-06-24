<script>
    let drop_area = $('.drop_area');
    // クリックで画像を選択する場合
    drop_area.on('click', function () {
        let parent = $(this).closest('.img');
        parent.find('.input_file').click();
    });

    $('.input_file').on('change', function () {
        let parent = $(this).closest('.img');
        // 画像が複数選択されていた場合
        if (this.files.length > 1) {
            alert('アップロードできる画像は1つだけです');
            $(this).val('');
            return;
        }
        handleFiles(parent, this.files);
    });
    // ドラッグしている要素がドロップ領域に入ったとき・領域にある間
    drop_area.on('dragenter dragover', function (event) {
        event.stopPropagation();
        event.preventDefault();
        $(this).addClass('active');
    });
    // ドラッグしている要素がドロップ領域から外れたとき
    drop_area.on('dragleave', function (event) {
        event.stopPropagation();
        event.preventDefault();
        $(this).removeClass('active');
    });
    // ドラッグしている要素がドロップされたとき
    drop_area.on('drop', function (event) {
        let parent = $(this).closest('.img');
        event.preventDefault();

        let input_file = parent.find('.input_file');
        input_file[0].files = event.originalEvent.dataTransfer.files;

        // 画像が複数選択されていた場合
        if (input_file[0].files.length > 1) {
            alert('アップロードできる画像は1つだけです');
            $('#input_file').val('');
            return;
        }
        handleFiles(parent, input_file[0].files);
    });
    // 選択された画像ファイルの操作
    function handleFiles(parent, files) {
        let file = files[0];
        let imageType = 'image.*';

        // ファイルが画像が確認する
        if (! file.type.match(imageType)) {
            alert('画像を選択してください');
            parent.find('.input_file').val('');
            parent.find('.drop_area').removeClass('active');
            return false;
        }

        // drop_areaを非表示
        parent.find('.drop_area').addClass('d-none');
        // icon_clear_buttonを表示
        parent.find('.btn_clear').removeClass('d-none');

        // <img>を作成
        let img = document.createElement('img');
        let reader = new FileReader();
        // 読み込み完了後処理
        reader.onload = function () {
            // readAsDataURLの読み込み結果がresult
            img.src = reader.result;
            // preview_filedに画像を表示
            parent.find('.preview_field').append(img);
        }
        // ファイル読み込みを非同期でバックグラウンドで開始
        reader.readAsDataURL(file);
    }

    // アイコン画像を消去するボタン
    $('.btn_clear').on('click', function () {
        let parent = $(this).closest('.img');
        // 表示画像を消去
        parent.find('.preview_field').empty();
        // inputの中身を消去
        parent.find('.input_file').val('');
        // icon_clear_buttonを非表示
        parent.find('.btn_clear').addClass('d-none');
        // 枠を点線に変更
        parent.find('.drop_area').removeClass('active').removeClass('d-none');
        // 各フォームを空にする
        parent.find('.image_type').val('');
        // parent.find('.caption').val('');
        parent.find('.caption').val('');

        // 既存画像削除フラグ
        let is_preexist = parent.find('.is_preexist').val();
        if(Number(is_preexist)) {
            parent.find('.is_del').val(1);
        }
    });

    // drop_area以外でファイルがドロップされた場合、ファイルが開いてしまうのを防ぐ
    $(document).on('dragenter', function (event) {
        event.stopPropagation();
        event.preventDefault();
    });
    $(document).on('dragover', function (event) {
        event.stopPropagation();
        event.preventDefault();
    });
    $(document).on('drop', function (event) {
        event.stopPropagation();
        event.preventDefault();
    });
</script>
