<?php

include("Conn.php");

// =========請求==========
// 設定CORS標頭，允許 'content-type' 標頭欄位。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");


// 撰寫 SQL 查詢
$sql = 
"SELECT COUNT( WL.GAME_ID ) AS COUNT from WISH_LIST WL
LEFT JOIN MEMBER_DATA MD on WL.MEMBER_ID = MD.MEMBER_ID
WHERE MD.MEMBER_ID = ? AND WL.GAME_ID = ? ;";


// 執行查詢
$statement = $pdo->prepare($sql);
$statement->bindValue(1,$_GET["id"]);
$statement->bindValue(2,$_GET["wishitem"]);
$statement->execute();
$result  = $statement->fetch();

// 檢查結果是否存在
if ($result["COUNT"] > 0) {
    // 遊戲 ID 存在於願望清單中，執行刪除操作
    $sqlDelete = "DELETE FROM WISH_LIST WHERE MEMBER_ID = ? AND GAME_ID = ?";

    $statementDelete = $pdo->prepare($sqlDelete);
    $statementDelete->bindValue(1, $_GET["id"]);
    $statementDelete->bindValue(2, $_GET["wishitem"]);
    $statementDelete->execute();

    echo "success";

}else if($result["COUNT"] == 0){
    // 遊戲 ID 不存在於願望清單中，執行插入操作
    $sqlInsert = "INSERT INTO WISH_LIST (MEMBER_ID, GAME_ID) VALUES (?, ?)";

    $statementInsert = $pdo->prepare($sqlInsert);
    $statementInsert->bindValue(1, $_GET["id"]);
    $statementInsert->bindValue(2, $_GET["wishitem"]);
    $statementInsert->execute();

    echo "success";

}else{
    echo "insert or delete failed";
}



?>
