<?php
// 包含 Conn.php 文件
include("Conn.php");

// 设置CORS标头，允许 'content-type' 标头字段。
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

// 获取从表单提交的数据
$name = $_POST['name'];
$nickname = $_POST['nickname'];
$phone = $_POST['phone'];
$gender = $_POST['gender'];
$email = $_POST['email'];

// 检查是否有上传图片
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
  // 处理图像文件的上传
  //  $imageDir = $_SERVER["DOCUMENT_ROOT"].'/brainstem_php/'; // 图像文件存储目录
   $imageDir_php = $photo_fileSystem_path;  // 圖片被儲存的路徑
   $imageDir_web = $get_photo_path; // 前端圖片渲染路徑
   
  //  如果沒有這資料夾就新建
  if(!file_exists($imageDir_php)){
    mkdir($imageDir_php,0777,true);
  }
  // $imageDir = '/Applications/XAMPP/xamppfiles/htdocs/brainstem/pic/img/member_photo/';
  $image = $_FILES['image']; // 获取图像文件

  $tmpName = $image['tmp_name']; // 临时文件路径
  $extension = pathinfo($image['name'], PATHINFO_EXTENSION); // 图像文件扩展名

  // 生成唯一的文件名
  $fileName = uniqid() . '.' . $extension;

  $src = 'pic/img/member_photo/';

  // 移动文件到目标目录
  $targetPath = $src . $fileName;
  $targetPaths_php = $imageDir_php . $fileName;
  $targetPaths_web = $imageDir_web . $fileName;
  if (move_uploaded_file($tmpName, $targetPaths_php)) {
    // 文件移动成功，将文件路径保存到数据库
    $sql = "UPDATE MEMBER_DATA SET USERNAME = ?, NICKNAME = ?, PHONE = ?, GENDER = ?, MEMBER_PHOTO = ? WHERE MEMBER_ACCOUNT = ?";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1, $name);
    $statement->bindValue(2, $nickname);
    $statement->bindValue(3, $phone);
    $statement->bindValue(4, $gender);
    $statement->bindValue(5, $targetPaths_web);
    $statement->bindValue(6, $email);

    $affectedRow = $statement->execute();

    if ($affectedRow > 0) {
      echo '更新成功';
      echo $targetPaths_web;
    } else {
      echo '发生错误：' . $statement->errorInfo()[2];
    }
  } else {
    echo '文件上传失败';
    echo $aaa;
  }
} else {
  // 没有上传图片的情况下，直接将其他数据保存到数据库
  $sql = "UPDATE MEMBER_DATA SET USERNAME = ?, NICKNAME = ?, PHONE = ?, GENDER = ? WHERE MEMBER_ACCOUNT = ?";
  $statement = $pdo->prepare($sql);
  $statement->bindValue(1, $name);
  $statement->bindValue(2, $nickname);
  $statement->bindValue(3, $phone);
  $statement->bindValue(4, $gender);
  $statement->bindValue(5, $email);

  $affectedRow = $statement->execute();

  if ($affectedRow > 0) {
    echo '更新成功';
    echo $imageDir;
  } else {
    echo '发生错误：' . $statement->errorInfo()[2];
  }
}
?>
