<?php
// 此檔為範例，請依據個人需求做更改

// 資料庫連線(這行一定要寫!!!!!!!!!)
include("Conn.php");

header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

//選擇遊戲尺度
$gameratingsql = "SELECT RATING_ID,RATING_NAME FROM GAME_RATING";
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 取得POST的資料
    $gameName = $_POST['gameName'];
    $gameIntro = $_POST['gameIntro'];
    $originalPrice = $_POST['originalPrice'];
    $gameStatus = $_POST['gameStatus'];
    $releaseDate = $_POST['releaseDate'];
    $publisher = $_POST['publisher'];
    $developer = $_POST['developer'];
    $ratingID = $_POST['ratingID'];
    $systemRequirement = $_POST['systemRequirement'];
    $totalComments = $_POST['totalComments'];
    $purchased = $_POST['purchased'];


    $categoryid = $_POST['categoryid'];
    $activityid = $_POST['activityid'];
    // echo $_POST['activityid'];
    // echo "111";
    // print_r($_FILES);
    // print_r($_POST);
    // 檢查是否有上傳圖片
    if (isset($_FILES['gameCover'])) {
        // var_dump($_FILES['gameCover'][0]);
        // 取得上傳的檔案資訊
        // echo "aaa";
        $file = $_FILES['gameCover'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];

        // 檢查檔案是否成功上傳
        if ($fileTmpName !== '') {
            // 將圖片搬移到適當的目錄，並取得新的路徑
            // $targetDir = './pic/img/cover/'; // 替換成您希望存儲圖片的目錄
            // var_dump($_FILES['gameCover']);

            $targetDir = 'C:\Users\Tibame_T14\Downloads/'; // 替換成您希望存儲圖片的目錄
            // $targetDir = $add_photo; // 替換成您希望存儲圖片的目錄
            $newFilePath = $targetDir . $fileName;


            if (move_uploaded_file($fileTmpName, $newFilePath)) {
                // echo $fileName;
            }

            // 設置圖片路徑
            $gameCover = $newFilePath;
        }
    } else {
        $gameCover = 'C:\Users\Tibame_T14\Downloads'; // 提供預設的圖片路徑
    }

    if (empty($gameStatus)) {
        $gameStatus = 1;
    }

    // 執行插入語句
    $sql = "INSERT INTO GAME_DATA (GAME_NAME, GAME_COVER, GAME_INTRO, ORIGINAL_PRICE, 
    GAME_STATUS, RELEASE_DATE, PUBLISHER, DEVELOPER, RATING_ID, SYSTEM_REQUIREMENT, 
    TOTAL_COMMENTS, PURCHASED) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = $pdo->prepare($sql);
    $statement->execute([
        $gameName, $gameCover, $gameIntro, $originalPrice, $gameStatus,
        $releaseDate, $publisher, $developer, $ratingID, $systemRequirement,
         $totalComments, $purchased
    ]);


    $sqlGAR = "INSERT INTO G_A_RELATION (ACTIVITY_ID) VALUES (?)";

    $statementGAR = $pdo->prepare($sqlGAR);
    $statementGAR->bindParam(1, $activityid); 
    $statementGAR->execute();
    // 關閉資料庫連線
    unset($pdo);
    echo print_r($_POST);
}

// 關閉資料庫連線
unset($pdo);

$data = array(
    'rating' => $rating,
    'category' => $category,
    'activity' => $activity
);

echo json_encode($data);

