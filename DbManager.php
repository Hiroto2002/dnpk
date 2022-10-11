<?php
function getDb(): PDO
{
  // $dsn = 'mysql:dbname=dnpk_dnpk_oes; host=127.0.0.1; charset=utf8';
  // $user = 'root';
  // $password = '';

  $dsn = 'mysql:dbname=dnpk_dnpk_oes; host=127.0.0.1; charset=utf8';

  $user = 'root';
  $password = '';


  try {
    // DB接続
    $db = new PDO($dsn, $user, $password); //ここ戻したら動くYO
    // $db = new PDO("mysql:host=localhost;dbname=dnpk_dnpk_oes;charset=utf8;port=8888", "root", "root");

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $db = new PDO($dsn, $usr, $passwd, [PDO::ATTR_PERSISTENT => true]);
  } catch (PDOException $e) {
    exit('接続できませんでした。理由：' . $e->getMessage());
  }
  return $db;
}

function fetch_all_query($db, $sql, $params = array())
{
  try {
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
  } catch (PDOException $e) {
    set_error('データ取得に失敗しました。');
  }
  return false;
}

function execute_query($db, $sql, $params = array())
{
  try {
    $statement = $db->prepare($sql);
    return $statement->execute($params);
  } catch (PDOException $e) {
    set_error('更新に失敗しました。');
  }
  return false;
}


function ChangeName($menu_id)
{
  $pdo = getDb();
  $sql = $pdo->prepare('SELECT mn_id,mn_Name_sub FROM t_m_menu where mn_id=? ORDER BY mn_Sort ASC');
  $sql->execute(array($menu_id));
  $product = $sql->fetch(PDO::FETCH_ASSOC);
  $mn_name = $product["mn_Name_sub"];

  return $mn_name;
}

function ChangePrice($menu_id)
{
  $pdo = getDb();
  $sql = $pdo->prepare('SELECT mn_Price FROM `t_m_menu` WHERE mn_ID = ?; ');
  $sql->execute(array($menu_id));
  $product = $sql->fetch(PDO::FETCH_ASSOC);
  $price = $product["mn_Price"];

  return $price;
}

function ChangeOptionID($option_name)
{
  $pdo = getDb();
  $sql = $pdo->prepare('SELECT opm_ID FROM `t_m_option_menu` WHERE opm_Name = ?; ');
  $sql->execute(array($option_name));
  $product = $sql->fetch(PDO::FETCH_ASSOC);
  $opm_ID = $product["opm_ID"];

  return $opm_ID;
}

function ChangeOptionName($option_ID)
{
  $pdo = getDb();
  $sql = $pdo->prepare('SELECT opm_Name FROM `t_m_option_menu` WHERE opm_ID = ?; ');
  $sql->execute(array($option_ID));
  $product = $sql->fetch(PDO::FETCH_ASSOC);
  $option_Name = $product["opm_Name"];

  return $option_Name;
}
