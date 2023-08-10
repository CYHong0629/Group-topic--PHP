<?php

include("Conn.php");

// 撰寫 SQL 查詢
$sql = "SELECT   O.CATEGORY_DESCRIPTION, C.CATEGORY_NAME, G.GAME_ID, G.GAME_NAME,G.GAME_COVER
FROM `CATEGORY`  AS C
JOIN `G_C_RELATION` AS GC ON C.CATEGORY_ID = GC.CATEGORY_ID
JOIN `GAME_DATA` AS G ON GC.GAME_ID = G.GAME_ID
join (select distinct CATEGORY_DESCRIPTION,CATEGORY_ID   from `AI_OPTION`
) as O on O.CATEGORY_ID =  C.CATEGORY_ID 
WHERE C.CATEGORY_ID = ? 
ORDER BY RAND() LIMIT 3";

// 執行查詢
$statement = $pdo->prepare($sql);
$statement->bindValue(1,$_GET['gameTypeNum']);
$statement->execute();
$testResult  = $statement->fetchAll();

// 建立最終的 JSON 格式陣列
$jsonArray = null;

if (count($testResult) > 0) {
    $gameType = "";
    $gameTypeContent = "";
    $game=array();

    // 逐筆取得 綜合遊戲資料表 的結果
    foreach($testResult as $index => $gameRow){

        $gameType = $gameRow["CATEGORY_NAME"];
        $gameTypeContent = $gameRow["CATEGORY_DESCRIPTION"];
        

        // 建立 game 內容陣列
        $gameArray = array(
            "id" => $gameRow["GAME_ID"],
            "name" => $gameRow["GAME_NAME"],
            "image" => $gameRow["GAME_COVER"],
            "url" => "",
        );

        // 將 game 內容陣列加入 game 陣列
        $game[] = $gameArray;
    }

    // 建立最終的 JSON 格式陣列
    $jsonArray = array(
    "gameType" => $gameType,
    "gameTypeContent" => $gameTypeContent,
    "game" => $game
    );
}

// 關閉資料庫連線
unset($pdo);

// 轉換成 JSON 格式
$jsonString = json_encode($jsonArray);

// 輸出 JSON
echo $jsonString;
?>
