$(document).ready(function() {
    // 予約した日付のボタンがクリックされたときにモーダルを開く
    $(document).on('click', '.reserve-modal-open', function() {
    // クリックされたら取得
    var reserveDate = $(this).data('reserve-date');
    var reserveTime = $(this).data('reserve-time');
     // クリックされたらを表示
    $('#modal-date').text(reserveDate);
    $('#modal-time').text(reserveTime);
    $('#reserve-modal').fadeIn();
  });

  // キャンセルボタンがクリックされたとき
  $('button.btn-danger').on('click', function() {
    $('#deleteParts').submit();  // フォームを送信
    $('#reserve-modal').fadeOut();  // モーダルを閉じる
});
    // モーダル閉じる処理
    $('.js-modal-close').on('click', function() {
        $('#reserve-modal').fadeOut();
    });
});