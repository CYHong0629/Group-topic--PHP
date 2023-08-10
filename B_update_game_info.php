<?php
include("Conn.php");

header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

// 檢查請求方法是否為POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 獲取從前端發送的表單數據
    $gameId = $_POST['gameId'];
    $gameName = $_POST['gameName'];
    $selectedCategories = $_POST['categorys'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $publisher = $_POST['publisher'];
    $developer = $_POST['developer'];
    $ratingID = $_POST['ratingID'];
    $originalPrice = $_POST['originalPrice'];
    $discountPercentage = $_POST['discountPercentage'];
    $discountedPrice = $_POST['discountedPrice'];
    $gameIntro = $_POST['gameIntro'];
    $systemRequirement = $_POST['systemRequirement'];
echo  $selectedCategories;
//
$selecttype = explode(",", $selectedCategories);
// print_r($selecttype);
//
    // 開始
    $pdo->beginTransaction();

    try {
        // 更新GAME_DATA資料表
        $sqlGameData = "UPDATE GAME_DATA SET
            GAME_NAME = :gameName,
            GAME_INTRO = :gameIntro,
            ORIGINAL_PRICE = :originalPrice,
            PUBLISHER = :publisher,
            DEVELOPER = :developer,
            RATING_ID = :ratingID,
            SYSTEM_REQUIREMENT = :systemRequirement,
            START_TIME = :startTime,
            END_TIME = :endTime,
            DISCOUNT_PERCENTAGE = :discountPercentage,
            DISCOUNTED_PRICE = :discountedPrice
            WHERE GAME_ID = :gameId";

////////////////////////////////////////////
$sqlGameData = "UPDATE GAME_DATA GD
LEFT JOIN GAME_RATING GR ON GD.RATING_ID = GR.RATING_ID
LEFT JOIN G_C_RELATION GCR ON GD.GAME_ID = GCR.GAME_ID
LEFT JOIN CATEGORY C ON GCR.CATEGORY_ID = C.CATEGORY_ID
LEFT JOIN G_A_RELATION GAR ON GD.GAME_ID = GAR.GAME_ID
LEFT JOIN ACTIVITY A ON GAR.ACTIVITY_ID = A.ACTIVITY_ID
SET
  GD.GAME_NAME = 'gameName',
  GD.GAME_COVER = '新遊戲封面',
  GD.GAME_INTRO = '新遊戲介紹',
  GD.ORIGINAL_PRICE = '新原價',
  GD.GAME_STATUS = '新遊戲狀態',
  GD.RELEASE_DATE = '新發行日期',
  GD.PUBLISHER = '新發行商',
  GD.DEVELOPER = '新開發商',
  GD.RATING_ID = '新評分ID',
  GD.SYSTEM_REQUIREMENT = '新系統需求',
  GD.TOTAL_COMMENTS = '新評論總數',
  GD.PURCHASED = '新購買數量',
  GR.RATING_NAME = '新評分名稱'
WHERE
  GD.GAME_ID = :gameId";
  ///////////////////////////////////////






        $statementGameData = $pdo->prepare($sqlGameData);
        $statementGameData->bindParam(':gameName', $gameName);
        $statementGameData->bindParam(':gameIntro', $gameIntro);
        $statementGameData->bindParam(':originalPrice', $originalPrice);
        $statementGameData->bindParam(':publisher', $publisher);
        $statementGameData->bindParam(':developer', $developer);
        $statementGameData->bindParam(':ratingID', $ratingID);
        $statementGameData->bindParam(':systemRequirement', $systemRequirement);
        $statementGameData->bindParam(':startTime', $startTime);
        $statementGameData->bindParam(':endTime', $endTime);
        $statementGameData->bindParam(':discountPercentage', $discountPercentage);
        $statementGameData->bindParam(':discountedPrice', $discountedPrice);
        $statementGameData->bindParam(':gameId', $gameId);
        $statementGameData->execute();

        // 更新GAME_RATING資料表
        $sqlGameRating = "UPDATE GAME_RATING SET
            RATING_NAME = :ratingName
            WHERE RATING_ID = :ratingID";

        $statementGameRating = $pdo->prepare($sqlGameRating);
        $statementGameRating->bindParam(':ratingName', $ratingName); // 請確認你從前端獲取的評分名稱的變量名稱
        $statementGameRating->bindParam(':ratingID', $ratingID);
        $statementGameRating->execute();

        // 更新G_C_RELATION資料表
        $sqlGCR = "UPDATE G_C_RELATION SET
            CATEGORY_ID = :categoryId
            WHERE GAME_ID = :gameId";

        $statementGCR = $pdo->prepare($sqlGCR);
        $statementGCR->bindParam(':categoryId', $categoryId); // 請確認你從前端獲取的類別ID的變量名稱
        $statementGCR->bindParam(':gameId', $gameId);
        $statementGCR->execute();

        // 更新CATEGORY資料表
        $sqlCategory = "UPDATE CATEGORY SET
            CATEGORY_NAME = :categoryName
            WHERE CATEGORY_ID = :categoryId";

        $statementCategory = $pdo->prepare($sqlCategory);
        $statementCategory->bindParam(':categoryName', $categoryName); // 請確認你從前端獲取的類別名稱的變量名稱
        $statementCategory->bindParam(':categoryId', $categoryId);
        $statementCategory->execute();

        // 更新G_A_RELATION資料表
        $sqlGAR = "UPDATE G_A_RELATION SET
            ACTIVITY_ID = :activityId
            WHERE GAME_ID = :gameId";

        $statementGAR = $pdo->prepare($sqlGAR);
        $statementGAR->bindParam(':activityId', $activityId); // 請確認你從前端獲取的活動ID的變量名稱
        $statementGAR->bindParam(':gameId', $gameId);
        $statementGAR->execute();

        // 更新ACTIVITY資料表
        $sqlActivity = "UPDATE ACTIVITY SET
            ACTIVITY_NAME = :activityName,
            ACTIVITY_BEGIN = :activityBegin,
            ACTIVITY_END = :activityEnd,
            DISCOUNT_PERCENTAGE = :discountPercentage,
            ACTIVITY_IMG = :activityImg
            WHERE ACTIVITY_ID = :activityId";

        $statementActivity = $pdo->prepare($sqlActivity);
        $statementActivity->bindParam(':activityName', $activityName); // 請確認你從前端獲取的活動名稱的變量名稱
        $statementActivity->bindParam(':activityBegin', $activityBegin); // 請確認你從前端獲取的活動開始時間的變量名稱
        $statementActivity->bindParam(':activityEnd', $activityEnd); // 請確認你從前端獲取的活動結束時間的變量名稱
        $statementActivity->bindParam(':discountPercentage', $discountPercentage); // 請確認你從前端獲取的折扣百分比的變量名稱
        $statementActivity->bindParam(':activityImg', $activityImg); // 請確認你從前端獲取的活動圖片的變量名稱
        $statementActivity->bindParam(':activityId', $activityId);
        $statementActivity->execute();

        // 提交事務
        $pdo->commit();

        // 返回成功的響應
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // 回滾
        $pdo->rollback();

        // 返回失敗的響應
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    // 關閉數據庫連接
    unset($pdo);
}
?>
