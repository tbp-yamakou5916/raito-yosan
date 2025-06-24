@section('js')
    {{-- https://datatables.net/download/ --}}
    <script>
        // DataTable 共通設定
        $.extend( $.fn.dataTable.defaults, {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Japanese.json'
            },
            // 情報表示 無効
            info: true,
            // 件数切替の値を10～50の10刻みにする
            lengthMenu: [ 20, 50, 100, 200, 500 ],
            // Ajax 呼び出し中に処理中の表示をする
            bProcessing: true,
            // 件数のデフォルトの値を50にする
            displayLength: 50,
            // 初期ソートの無効
            order: [],
            autowidth: false,
            // ヘッダー固定 要プラグイン（上記URL参照）
            //fixedHeader: true,
            // 状態を保存
            //stateSave: true,
        });
        // dataTable ページャー ページトップへ
        $(document).on('click', '.paginate_button', function() {
            $(window).scrollTop(0)
        });
    </script>
    @parent
@endsection


