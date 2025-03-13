$(function () {
  $('.search_conditions').click(function () {
    $('.search_conditions_inner').slideToggle();
  });

  $('.subject_edit_btn').click(function () {
    $('.subject_inner').slideToggle();
  });
});


// カテゴリー検索アコーディオンメニュー
document.addEventListener('DOMContentLoaded', function() {
  const mainCategories = document.querySelectorAll('.main_categories');

  mainCategories.forEach(category => {
    category.addEventListener('click', function() { //各メインカテゴリー読み込む
      const subCategories = this.nextElementSibling; // サブカテゴリーのulを取得
      const navBtn = this.querySelector('.nav-btn'); // 矢印ボタンを取得

      if (!subCategories || !subCategories.classList.contains('sub_category_list')) return; //サブカテゴリーがなかったらreturnで返す


      // 他のメニューを閉じる（開いている場合）
      document.querySelectorAll('.sub_category_list').forEach(menu => { //全てのsub_category_listを取得
        if (menu !== subCategories) {
          menu.style.display = 'none';//クリックしたsubCategoriesと違う場合閉じる
          const prevNavBtn = menu.previousElementSibling.querySelector('.nav-btn');
          if (prevNavBtn) prevNavBtn.classList.remove('open'); // 他の矢印を元に戻す　prevNavBtnで他の矢印を元の向きに戻す
        }
      });

      // サブカテゴリー開閉
     if (subCategories.style.display === 'none' || subCategories.style.display === '') {
      subCategories.style.display = 'block';
      if (navBtn) navBtn.classList.add('open'); // 矢印を上向きに
    } else {
      subCategories.style.display = 'none';
      if (navBtn) navBtn.classList.remove('open'); // 矢印を元に戻す
    }
  });
});
});