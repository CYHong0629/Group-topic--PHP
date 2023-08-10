<?php

include("Conn.php");

// 撰寫 SQL 查詢
$sql = 
"WITH C AS (SELECT MD.NICKNAME, MD.MEMBER_PHOTO, GL.GAME_TIME, CL.*
FROM COMMENT_LIST CL
LEFT JOIN MEMBER_DATA MD ON CL.MEMBER_ID = MD.MEMBER_ID
LEFT JOIN GAME_LIBRARY GL ON CL.GAME_ID = GL.GAME_ID 
WHERE CL.GAME_ID = GL.GAME_ID AND CL.MEMBER_ID = GL.MEMBER_ID)
SELECT *
FROM C";

$sql_where = " WHERE C.GAME_ID = ?"; 

if(empty($_GET["id"])){
    $statement = $pdo->prepare($sql);
}else{
    $sql =  $sql.$sql_where;
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1, $_GET["id"]);
}

// 執行查詢
$statement->execute();

//抓出全部且依照順序封裝成一個二維陣列
$gameComment  = $statement->fetchAll();

// 建立最終的 JSON 格式陣列
// $jsonArray = array();

// -----------在這下面做資料處理回傳你想要的格式-----------------

// if (count($testResult) > 0) {
//     foreach($testResult as $index => $testRow){

//     }
// }
// -----------在這上面做資料處理回傳你想要的格式-----------------

// 關閉資料庫連線
// unset($pdo); 

// 轉換成 JSON 格式輸出 
echo json_encode($gameComment);
?>
