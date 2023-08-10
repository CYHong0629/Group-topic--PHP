<?php

include("Conn.php");

if (isset($_GET['id'])) {
    // 獲取URL中的遊戲ID參數
    $gameid = $_GET['id'];

    // 撰寫 SQL 查詢，根據遊戲ID篩選數據
    $sql = "SELECT gd.GAME_ID, gd.GAME_NAME, gd.GAME_COVER, gd.GAME_INTRO, FORMAT(gd.ORIGINAL_PRICE, 0) AS ORIGINAL_PRICE, gd.GAME_STATUS, gd.RELEASE_DATE, gd.PUBLISHER, gd.DEVELOPER, gd.RATING_ID, gd.SYSTEM_REQUIREMENT, gd.TOTAL_COMMENTS, gd.PURCHASED, gr.RATING_NAME, a.ACTIVITY_NAME, a.ACTIVITY_BEGIN, a.ACTIVITY_END, 
    FORMAT(gd.ORIGINAL_PRICE * (1 - a.DISCOUNT_PERCENTAGE/100), 0) AS DISCOUNTED_PRICE, 
    a.DISCOUNT_PERCENTAGE, 
    GROUP_CONCAT(c.CATEGORY_NAME) AS CATEGORY_NAMES
FROM GAME_DATA gd
LEFT JOIN GAME_RATING gr ON gd.RATING_ID = gr.RATING_ID
LEFT JOIN G_A_RELATION gar ON gd.GAME_ID = gar.GAME_ID
LEFT JOIN ACTIVITY a ON gar.ACTIVITY_ID = a.ACTIVITY_ID
LEFT JOIN G_C_RELATION gcr ON gd.GAME_ID = gcr.GAME_ID
LEFT JOIN CATEGORY c ON gcr.CATEGORY_ID = c.CATEGORY_ID 
WHERE gd.GAME_ID = :gameid
GROUP BY gd.GAME_ID, gd.GAME_NAME, gd.GAME_COVER, gd.GAME_INTRO, gd.ORIGINAL_PRICE, gd.GAME_STATUS, gd.RELEASE_DATE, gd.PUBLISHER, gd.DEVELOPER, gd.RATING_ID, gd.SYSTEM_REQUIREMENT, gd.TOTAL_COMMENTS, gd.PURCHASED, gr.RATING_NAME, a.ACTIVITY_NAME, a.ACTIVITY_BEGIN, a.ACTIVITY_END, a.DISCOUNT_PERCENTAGE";

    //選擇遊戲尺度
    $gameratingsql="SELECT RATING_ID,RATING_NAME FROM GAME_RATING";
    $gameratingstatement = $pdo->prepare($gameratingsql);
    $gameratingstatement->execute();

    $rating = $gameratingstatement->fetchAll(PDO::FETCH_ASSOC);

    //選擇遊戲類型
    $gamecategorysql = "SELECT CATEGORY_ID,CATEGORY_NAME FROM CATEGORY";
    $gamecategorystatement = $pdo->prepare($gamecategorysql);
    $gamecategorystatement->execute();

    $category = $gamecategorystatement->fetchAll(PDO::FETCH_ASSOC);

    //選擇活動類型
    $activitysql = "SELECT * FROM ACTIVITY";
    $activitystatement = $pdo->prepare($activitysql);
    $activitystatement->execute();

    $activity = $activitystatement->fetchAll(PDO::FETCH_ASSOC);







    // 執行查詢
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':gameid', $gameid);
    $statement->execute();

    // 获取单个游戏的详细信息
    $game = $statement->fetch(PDO::FETCH_ASSOC);

    $data = array(
        'game' => $game,
        'rating' => $rating,
        'category' => $category,
        'activity' => $activity
    );

    // 關閉數據庫連接
    unset($pdo);

    // 輸出遊戲詳細信息
    echo json_encode($data);
} else {
    echo "缺少游戲ID參數";
}
?>