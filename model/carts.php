<?php

// カートの商品データ
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      sample_items.item_id,
      sample_items.name,
      sample_items.price,
      sample_items.stock,
      sample_carts.cart_id,
      sample_carts.user_id,
      sample_carts.amount
    FROM
      sample_carts
    JOIN
      sample_items
    ON
      sample_carts.item_id = sample_items.item_id
    WHERE
      sample_carts.user_id = ?
  ";
  return fetch_all_query($db, $sql, array($user_id));
}

// カートの商品の合計額
function sum_carts($carts){
    $total_price = 0;
    foreach($carts as $cart){
        $total_price += $cart['price'] * $cart['amount'];
    }
    return $total_price;
}

// 購入処理
function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  // 購入後、カートの中身削除&在庫変動
  $db->beginTransaction();
  try {
    foreach($carts as $cart){    
      if(update_stock($db, $cart['item_id'], $cart['stock'] - $cart['amount']) === false){
          set_error($cart['name'] . 'の購入に失敗しました。');
        }
      }
      delete_user_carts($db, $carts[0]['user_id']);
      $db->commit();
  }catch(PDOException $e){
    $db->rollback();
    throw $e;
  }
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      sample_carts
    WHERE
      user_id = ?
  ";
  execute_query($db, $sql, array($user_id));
}

// バリデーション
function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

// カートの商品の削除
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      sample_carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  return execute_query($db, $sql, array($cart_id));
}

/?>