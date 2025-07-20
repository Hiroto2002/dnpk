"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
/* ピッチインピッチアウトによる拡大縮小を禁止 */
document.documentElement.addEventListener("touchstart", function (e) {
    if (e.touches.length >= 2) {
        e.preventDefault();
    }
}, {
    passive: false,
});
/* ダブルタップによる拡大を禁止 */
var t = 0;
document.documentElement.addEventListener("touchend", function (e) {
    var now = new Date().getTime();
    if (now - t < 350) {
        e.preventDefault();
    }
    t = now;
}, false);
setInterval(() => {
    location.reload();
}, 60000);
document.addEventListener("DOMContentLoaded", () => {
    const deleteLinks = document.querySelectorAll(".delete");
    deleteLinks.forEach((link) => {
        link.addEventListener("click", (event) => {
            event.preventDefault();
            if (!event.currentTarget) {
                console.error("No data-id attribute found on the clicked element.");
                return;
            }
            const orderId = event.currentTarget.dataset.id;
            const res1 = confirm(`オーダー番号${orderId}を削除しますか？`);
            console.log(`Order ID: ${orderId}`);
            if (res1) {
                const res2 = confirm("本当に削除しますか？");
                if (res2) {
                    window.location.href = "order_del.php?odh_No=" + orderId;
                }
            }
            else {
                console.log("削除がキャンセルされました");
            }
        });
    });
});
$(function () {
    $(".reload").on("click", function () {
        location.reload();
    });
});
$(function () {
    $(".widget-list-link2").on("click", function (e) {
        return __awaiter(this, void 0, void 0, function* () {
            e.preventDefault();
            const odhNo = $(this).attr("data-id");
            const situNo = $(this).attr("id");
            const url = $(this).attr("href");
            if (odhNo === undefined || situNo === undefined || url === undefined) {
                return;
            }
            yield fetch(`./api/checkSituation.php?odhNo=${odhNo}&situNo=${situNo}`)
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
                }
                else {
                    location.href = url;
                }
            })
                .catch((error) => {
                console.error("エラー:", error);
                alert("エラーが発生しました");
            });
        });
    });
});
