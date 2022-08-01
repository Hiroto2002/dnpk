<?php
// ログイン
function get_user_by_name($db, $name){
    $sql = "
      SELECT
        user_id,
        user_name,
        password
      FROM
        sample_users
      WHERE
        user_name = ?
    ";
    return fetch_all_query($db, $sql, array($name));
}

function login_as($db, $name, $password){
    $user = get_user_by_name($db, $name);
    if($user === false || $user['password'] !== $password){
        return false;
    }
    set_session('user_id', $user['user_id']);
    return $user;
}

// ユーザ登録
function regist_user($db, $name, $password) {
    if(is_valid_user($name, $password) === false){
        return false;
    }
    return insert_user($db, $name, $password);
}

// バリデーション
function is_valid_user($name, $password){
    // 短絡評価を避けるため一旦代入。
    $is_valid_user_name = is_valid_user_name($name);
    $is_valid_password = is_valid_password($password);
    return $is_valid_user_name && $is_valid_password ;
}

function is_valid_user_name($name) {
    $is_valid = true;
    if(is_valid_length($name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
      set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
      $is_valid = false;
    }
    if(is_alphanumeric($name) === false){
      set_error('ユーザー名は半角英数字で入力してください。');
      $is_valid = false;
    }
    return $is_valid;
}

function is_valid_password($password){
    $is_valid = true;
    if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){
      set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');
      $is_valid = false;
    }
    if(is_alphanumeric($password) === false){
      set_error('パスワードは半角英数字で入力してください。');
      $is_valid = false;
    }
    return $is_valid;
}

// ユーザデータの挿入
function insert_user($db, $name, $password){
    $sql = "
      INSERT INTO
        sample_users(user_name, password)
      VALUES (?,?);
    ";
    return execute_query($db, $sql, array($name, $password));
}

// ログイン状況
function get_user($db, $user_id){
    $sql = "
      SELECT
        user_id, 
        user_name,
        password
      FROM
        sample_users
      WHERE
        user_id = ?
    ";
    return fetch_all_query($db, $sql, array($user_id));
}

function get_login_user($db){
    $login_user_id = get_session('user_id');  
    return get_user($db, $login_user_id);
}  

?>