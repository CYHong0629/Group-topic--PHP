<?php

include("Conn.php");

// 撰寫 SQL 查詢
$sql = "SELECT * FROM AI_TEST";

// 執行查詢
$statement = $pdo->prepare($sql);
$statement->execute();

//抓出全部且依照順序封裝成一個二維陣列
$testResult  = $statement->fetchAll();

// 建立最終的 JSON 格式陣列
$jsonArray = array();

if (count($testResult) > 0) {
    // 逐筆取得 TEST 資料表的結果
    foreach($testResult as $index => $testRow){
        $questionNum = $testRow["TEST_ITEM_ID"];
        $question = $testRow["ITEM_NAME"];

        // 建立 option 陣列，儲存選項的資料
        $optionArray = array();

        // 執行查詢 OPTION 資料表
        $optionQuery = "SELECT * FROM `AI_OPTION` WHERE TEST_ITEM_ID = ? ";
        $optionResult = $pdo->prepare($optionQuery);
        $optionResult->bindValue(1, $questionNum);
        $optionResult->execute();
        $data = $optionResult->fetchAll();
        
        
        if (count($data) > 0) {
                // 逐筆取得 OPTION 資料表的結果
            foreach($data as $index => $optionRow){

                $opContent = $optionRow["OPTION_CONTENT"];
                $gameType = $optionRow["CATEGORY_ID"];

                // 建立 option 內容陣列
                $optionContentArray = array(
                    "opContent" => $opContent,
                    "gameType" => $gameType
                );

                // 將 option 內容陣列加入 option 陣列
                $optionArray[] = $optionContentArray;
            }
        }

        // 建立最終的 JSON 格式陣列
        $jsonArray[] = array(
            "questionNum" => $questionNum,
            "question" => $question,
            "option" => $optionArray
        );
    }
}

// 關閉資料庫連線
unset($pdo);

// 轉換成 JSON 格式
$jsonString = json_encode($jsonArray);

// 輸出 JSON
echo $jsonString;
?>
