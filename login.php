<?php
// 包含 Conn.php 文件
include("Conn.php");
session_start();


// 設定CORS標頭，允許 'content-type' 標頭欄位。
 header("Access-Control-Allow-Methods: GET, POST");
 header("Access-Control-Allow-Headers: Content-Type");
 header("Access-Control-Allow-Credentials: true");

  // 獲取從表單提交的資料
  $username = $_POST["account"];
  $password = $_POST["password"];

  // 在這裡進行資料驗證、處理和寫入資料庫的相關程式碼
  // 請注意安全性，如資料驗證、避免 SQL 注入等

  // 範例：將資料寫入資料庫
  // 使用 Conn.php 文件中的 $pdo 物件

    $sql = "SELECT COUNT(*) FROM MEMBER_DATA WHERE MEMBER_ACCOUNT = ? AND MEMBER_PASSWORD = ?";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1, $username);
    $statement->bindValue(2, $password);
    $statement->execute();

    $count = $statement->fetchColumn();

    if ($count > 0) {
        //帳號存入session
        $_SESSION["member_account"] = $username;
        echo "0";
    } else {
        echo "1";
    }

?>
