<?php
include("Conn.php");

// =========請求==========
// 設定CORS標頭，允許 'content-type' 標頭欄位。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");


// OPTIONS 请求方法是 CORS 的预检请求，用于确定是否可以发送实际的 POST 请求。在某些情况下，浏览器会发送 OPTIONS 请求以检查服务器是否允许跨域访问。
// 对于 OPTIONS 请求，只发送 CORS 头信息，不做其他处理
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

session_start();
$jsonArray = array(
    "isLogin"=>false
);
// ==================================

if(isset($_SESSION["member_account"])){
    
    // print_r($_SESSION['member_account']);
    // 判斷是否是google登入
    $sql = "SELECT NICKNAME,MEMBER_PHOTO,MEMBER_ID FROM MEMBER_DATA WHERE MEMBER_ACCOUNT = ?";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1,$_SESSION['member_account']);
    $statement->execute();
    $data = $statement->fetch(PDO::FETCH_OBJ);
    // echo $data->NICKNAME.$data->MEMBER_PHOTO;

    $jsonArray = array(
        "account" => $_SESSION["member_account"],
        "member_ID" => $data->MEMBER_ID,
        "nickname" => $data->NICKNAME,
        "photo" => $data->MEMBER_PHOTO,
        "isLogin"=>true
        );
    
}
echo json_encode( $jsonArray);

?>