<?php
include("Conn.php");

// =========請求==========
// 設定CORS標頭，允許 'content-type' 標頭欄位。

header("Access-Control-Allow-Credentials: true");

//最低金額跟最高金額設定預設值
$minPrice = 0;
$maxPrice = PHP_INT_MAX;

//撰寫 SQL 查詢
$sql = 
"WITH GAME AS (
SELECT GD.*, 
    AC.ACTIVITY_ID, AC.ACTIVITY_NAME, AC.DISCOUNT_PERCENTAGE,
    AC.ACTIVITY_BEGIN, AC.ACTIVITY_END,CATE.CATEGORY_ID,
    CATE.CATEGORY_NAME,GR.RATING_NAME
FROM GAME_DATA GD  
LEFT JOIN G_A_RELATION GA ON GD.GAME_ID = GA.GAME_ID
LEFT JOIN ACTIVITY AC ON GA.ACTIVITY_ID = AC.ACTIVITY_ID  
LEFT JOIN G_C_RELATION GC ON GD.GAME_ID = GC.GAME_ID
LEFT JOIN CATEGORY CATE ON GC.CATEGORY_ID = CATE.CATEGORY_ID
LEFT JOIN GAME_RATING GR ON GD.RATING_ID = GR.RATING_ID
) 
SELECT 
    GAME.GAME_ID,
    GAME.GAME_NAME,
    GAME.GAME_COVER,
    GAME.GAME_INTRO,
    GAME.ORIGINAL_PRICE,
    GAME.GAME_STATUS,
    GAME.RELEASE_DATE,
    GAME.PUBLISHER,
    GAME.DEVELOPER,
    GAME.RATING_NAME,
    GAME.SYSTEM_REQUIREMENT,
    GAME.TOTAL_COMMENTS,
    GAME.PURCHASED,
    ANY_VALUE(GAME.ACTIVITY_NAME) as ACTIVITY_NAME,
    ANY_VALUE(GAME.ACTIVITY_ID) as ACTIVITY_ID,
    ANY_VALUE(GAME.DISCOUNT_PERCENTAGE) as DISCOUNT_PERCENTAGE,
    ANY_VALUE(GAME.ACTIVITY_BEGIN) as ACTIVITY_BEGIN,
    ANY_VALUE(GAME.ACTIVITY_END) as ACTIVITY_END,
    ANY_VALUE(GROUP_CONCAT(GAME.CATEGORY_NAME SEPARATOR ',')) as GAME_CATE,
    ANY_VALUE(GROUP_CONCAT(GAME.CATEGORY_ID SEPARATOR ',')) as GAME_CATE_ID

from GAME 
LEFT JOIN G_A_RELATION GA ON GAME.GAME_ID = GA.GAME_ID
where GAME.GAME_STATUS = 1 AND 1=1";

if(isset($_GET["cateId"])){
    $sql = $sql." and GAME.CATEGORY_ID in(".$_GET["cateId"].")";
}

if(isset($_GET["id"])){
    $sql = $sql." and GAME.ACTIVITY_ID in(".$_GET["id"].")";
}

if(isset($_GET["filterlist"]["saleFilterList"])){
    $saleFilterList = $_GET["filterlist"]["saleFilterList"];
    $sql_saleFilterList ="";
    foreach($saleFilterList as $sale){
        $sql_saleFilterList = "{$sql_saleFilterList}'{$sale}',";
    };
    $sql_saleFilterList = substr($sql_saleFilterList,0,-1);
    // $saleFilterList = implode(',',$saleFilterList);
    $sql = $sql." and GAME.ACTIVITY_NAME in(".$sql_saleFilterList.")";
};

if(isset($_GET["filterlist"]["cateFilterList"])){
    $cateFilterList = $_GET["filterlist"]["cateFilterList"];
    $sql_cateFilterList ="";
    foreach($cateFilterList as $cate){
        $sql_cateFilterList = "{$sql_cateFilterList}'{$cate}',";
    };
    $sql_cateFilterList = substr($sql_cateFilterList,0,-1);
    // $cateFilterList = implode(',',$cateFilterList);
    $sql = $sql." and GAME.CATEGORY_NAME in(".$sql_cateFilterList.")";
};

if(!empty($_GET["filterlist"]["minPrice"])){
    $minPrice = $_GET["filterlist"]["minPrice"];  
};
if(!empty($_GET["filterlist"]["maxPrice"])){
    $maxPrice = $_GET["filterlist"]["maxPrice"];  
};

$sql = $sql." and GAME.ORIGINAL_PRICE between {$minPrice} and {$maxPrice}";

$sql .= " GROUP BY GAME.GAME_ID;";

// 執行查詢
$statement = $pdo->prepare($sql);
$statement->execute();



//抓出全部且依照順序封裝成一個二維陣列
$result  = $statement->fetchAll();

echo json_encode($result);
?>
