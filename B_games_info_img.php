<?php

include("Conn.php");

if (isset($_GET['id'])) {
    // 獲取URL中的遊戲ID參數
    $gameid = $_GET['id'];

    // 撰寫 SQL 查詢，根據遊戲ID篩選數據
    $sql = "SELECT gd.GAME_ID, gi.IMG_PATH, gi.IMG_DESCRIPTION
    FROM GAME_DATA gd
    JOIN GAME_IMG gi ON gd.GAME_ID = gi.GAME_ID
    WHERE gd.GAME_ID = :gameid;";


    // 執行查詢
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':gameid', $gameid);
    $statement->execute();

    // 獲取單個遊戲的詳細信息
    $game = $statement->fetchAll(PDO::FETCH_ASSOC);
    // $systemRequirement = $game['SYSTEM_REQUIREMENT'];
    // echo $systemRequirement;

    // 關閉數據庫連接
    unset($pdo);

    // 輸出遊戲詳細信息
    echo json_encode($game);
} else {
    echo "缺少游戲ID參數";
}
?>