$(document).ready(function() {
    // 予約した日付のボタンがクリックされたときにモーダルを開く
    $(document).on('click', '.reserve-modal-open', function(event) {
        event.preventDefault(); // フォーム送信を防ぐ（重要！）

    // クリックされたら取得
    var reserveDate = $(this).data('reserve-date');
    var reserveTime = $(this).data('reserve-time');
     // クリックされたらを表示
    $('#modal-date').text(reserveDate);
    $('#modal-time').text(reserveTime);
    $('#reserve-modal').fadeIn();
  });

  // キャンセルボタンがクリックされたとき
    $(document).on('click', '.js-delete-reserve', function(event) {
    event.preventDefault();
    $('#deleteParts').submit(); // フォームを送信
    $('#reserve-modal').fadeOut();  // モーダルを閉じる
});
    // モーダル閉じる処理
    $('.js-modal-close').on('click', function() {
        $('#reserve-modal').fadeOut();
    });

    $(window).on('click', function(e) {
        if ($(e.target).is('#reserve-modal')) {
            $('#reserve-modal').fadeOut();
        }
    });
});