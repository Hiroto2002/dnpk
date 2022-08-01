<?php
require_once MODEL_PATH. 'functions.php';
require_once MODEL_PATH. 'db.php';

// Create
function regist_item($db, $name, $price, $stock){
  $sql = "
    INSERT INTO
      sample_items(
        name,
        price,
        stock
      )
    VALUES(?,?,?)
  ";
  return execute_query($db, $sql, array($name, $price, $stock));
}

// Read
function get_items($db){
  $sql = "
    SELECT
      item_id,
      name,
      price,
      stock
    FROM
      sample_items
  ";
  return fetch_all_query($db, $sql);
}

// Update
function update_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      sample_items
    SET
      stock = ?
    WHERE
      item_id = ?
  ";
  return execute_query($db, $sql, array($stock, $item_id));
}

// Delete
function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      sample_items
    WHERE
      item_id = ?
  ";
  return execute_query($db, $sql, array($item_id));
}