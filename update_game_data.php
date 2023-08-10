<?php
// 包含 Conn.php 文件
include("Conn.php");

// 设置CORS标头，允许 'content-type' 标头字段。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

// 获取从表单提交的数据
$member_ID = $_POST['member_ID'];
$gameId = $_POST['gameId'];
$playTime = $_POST['playTime'];
$lastPlayDate = $_POST['lastPlayDate'];
$achievement_num = $_POST['achievement_num'];


    $sql = "UPDATE GAME_LIBRARY SET GAME_TIME = ?, LAST_PLAYED = ?, ACHIEVEMENT = ? WHERE MEMBER_ID = ? AND GAME_ID = ?";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1, $playTime);
    $statement->bindValue(2, $lastPlayDate);
    $statement->bindValue(3, $achievement_num);
    $statement->bindValue(4, $member_ID);
    $statement->bindValue(5, $gameId);

    $affectedRow = $statement->execute();

    if ($affectedRow > 0) {
      echo '更新成功';
    } else {
      echo '发生错误：';
    }
  
?>
