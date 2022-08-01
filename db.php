<?php

function get_db_connect(){
    // MySQL用のDSN文字列
    $dsn = 'mysql:dbname=dnpk_dnpk_oes;host=mysql57.dnpk.sakura.ne.jp;charset=utf8';
    $user = 'dnpk';
    $password = '7ujmnhy6';
  
    try{
      // DB接続
      $dbh = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        exit('接続できませんでした。理由：'.$e->getMessage());
    }
    return $dbh;
}

function execute_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    return $statement->execute($params);
  }catch(PDOException $e){
    set_error('更新に失敗しました。');  
  }
  return false;
}

function fetch_all_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}


// function getDb() : PDO {
//   $dsn = 'mysql:dbname=dnpk_dnpk_oes; host=mysql57.dnpk.sakura.ne.jp; charset=utf8';
//   $user = 'dnpk';
//   $password = '7ujmnhy6';

//   $db = new PDO($dsn, $user, $password);
//   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//   // $db = new PDO($dsn, $usr, $passwd, [PDO::ATTR_PERSISTENT => true]);
//   return $db;
// }

?>
