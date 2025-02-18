/* ピッチインピッチアウトによる拡大縮小を禁止 */
document.documentElement.addEventListener(
  "touchstart",
  function (e) {
    if (e.touches.length >= 2) {
      e.preventDefault();
    }
  },
  {
    passive: false,
  }
);
/* ダブルタップによる拡大を禁止 */
var t = 0;
document.documentElement.addEventListener(
  "touchend",
  function (e) {
    var now = new Date().getTime();
    if (now - t < 350) {
      e.preventDefault();
    }
    t = now;
  },
  false
);
setInterval(() => {
  location.reload();
}, 60000);

function MoveCheck($str: string) {
  var res = confirm("オーダー番号" + $str + "を削除しますか？");
  if (res == true) {
    // 再度確認
    var res = confirm("本当に削除しますか？");
    if (res == true) {
      //OKなら削除処理に進む
    } else {
      // キャンセルなら処理を中断する
      return false;
    }
  } else {
    // キャンセルなら処理を中断する
    return false;
  }
}
$(function () {
  $(".reload").on("click", function () {
    location.reload();
  });
});

$(function () {
  $(".widget-list-link2").on("click", async function (e) {
    e.preventDefault();
    const odhNo = $(this).attr("data-id");
    const situNo = $(this).attr("id");
    const url = $(this).attr("href");
    if (odhNo === undefined || situNo === undefined || url === undefined) {
      alert("エラーが発生しました");
      return;
    }

    await fetch(`./api/checkSituation.php?odhNo=${odhNo}&situNo=${situNo}`)
      .then((res) => {
        if (!res.ok) {
          throw new Error("サーバーエラーが発生しました");
        }
        return res.json();
      })
      .then((data) => {
        if (data === false) {
          alert("ぐるぐるで更新してください");
          location.reload();
        } else {
          location.href = url;
        }
        // 他のデータ処理をここに追加
      })
      .catch((error) => {
        console.error("エラー:", error);
        alert("エラーが発生しました");
      });
  });
});
