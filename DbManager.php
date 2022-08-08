<?php
function getDb() : PDO {
  $dsn = 'mysql:dbname=dnpk_dnpk_oes; host=127.0.0.1; charset=utf8';
  $user = 'root';
  $password = '';

  try{
    // DB接続
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // $db = new PDO($dsn, $usr, $passwd, [PDO::ATTR_PERSISTENT => true]);
  }catch(PDOException $e){
      exit('接続できませんでした。理由：'.$e->getMessage());
  }
  return $db;

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

function execute_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    return $statement->execute($params);
  }catch(PDOException $e){
    set_error('更新に失敗しました。');  
  }
  return false;
}


    function ChangeName($menu_id){
        $pdo = getDb();

        $sql = $pdo->prepare('SELECT mn_id,mn_Name_sub FROM t_m_menu where mn_id=? ORDER BY mn_Sort ASC');
        $sql->execute(array($menu_id));
        $product = $sql->fetch(PDO::FETCH_ASSOC);
        $mn_name = $product["mn_Name_sub"];

        return $mn_name;
    }
?>