$(document).ready(function() {
    // 予約ボタンがクリックされたときにモーダルを開く
    $(document).on('click', '.reserve-modal-open', function() {
    
    var reserveDate = $(this).data('reserve-date');
    var reserveTime = $(this).data('reserve-time');
    
    $('#modal-date').text(reserveDate);
    $('#modal-time').text(reserveTime);
    
    // モーダルを表示
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