<?php 
require_once 'functions.php';
require_once 'db.php';

// カートに追加するために必要なデータ
function get_user_cart($db, $mn_ID){
  $sql = "
    SELECT
      t_m_menu.mn_ID,
      t_m_menu.mn_Name_sub,
      t_m_menu.mn_Price,
      t_d_cart.ct_id,
      t_d_cart.amount
    FROM
      t_d_cart
    JOIN
      t_m_menu
    ON
      t_d_cart.mn_ID = t_m_menu.mn_ID
    WHERE
      t_m_menu.mn_ID = ?
  ";
  return fetch_all_query($db, $sql, array($mn_ID));
}

// カートに追加(既に同じ商品があれば、個数のみUpdate)
function add_cart($db,  $mn_ID) {
  $cart = get_user_cart($db, $mn_ID);
  if($cart === false){
    return insert_cart($db, $mn_ID);
  }
  return update_cart_amount($db, $cart['ct_id'], $cart['amount'] + 1);
}

function insert_cart($db, $mn_ID, $amount = 1){
  $sql = "
    INSERT INTO
      t_d_cart(
        mn_ID,
        amount
      )
    VALUES(?,?)
  ";
  return execute_query($db, $sql, array($mn_ID, $amount));
}

function update_cart_amount($db, $ct_id, $amount){
  $sql = "
    UPDATE
      t_d_cart
    SET
      amount = ?
    WHERE
    ct_id = ?
    LIMIT 1
  ";
  return execute_query($db, $sql, array($amount, $ct_id));
}