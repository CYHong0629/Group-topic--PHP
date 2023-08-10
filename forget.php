<?php
include("Conn.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

$email = $_POST['email'];
$verificationCode = $_POST['verificationCode'];


// 构建查询语句，检查是否存在匹配的MEMBER_ACCOUNT
$sql = "SELECT COUNT(*) FROM MEMBER_DATA WHERE MEMBER_ACCOUNT = ?";
$statement = $pdo->prepare($sql);
$statement->bindValue(1, $email);
$statement->execute();

// 获取查询结果
$result = $statement->fetchColumn();

// 根据查询结果返回相应的值
if ($result > 0) {
    // 发送邮件
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->SMTPAutoTLS = false;
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->Username = 'brainstem.thd101@gmail.com'; // 你的 Gmail 邮箱地址
    $mail->Password = 'gqwjsvzsikiinomr'; // 你的 Gmail 邮箱密码
    $mail->SMTPSecure = 'ssl';

    // 设置发件人姓名
    $mail->setFrom('brainstem.thd101@gmail.com', 'Brainstem'); // 替换为你的发件人姓名和邮箱地址

    // 设置收件人邮箱地址和姓名
    $mail->addAddress($email); // 使用用户填写的邮箱地址作为收件人
    $mail->addReplyTo('brainstem.thd101@gmail.com', 'Brainstem'); // 替换为你的回复邮箱地址和姓名

    // 设置邮件主题和内容
    $mail->Subject = 'Brainstem忘記密碼_驗證碼';
    $mail->Body = '您的驗證碼是：' . $verificationCode;

    // 发送邮件
    if ($mail->send()) {
        if (isset($_POST['verificationCode']) && isset($_POST['newPassword'])) {
            // 更新数据库密码
            $new_password = $_POST['newPassword'];
            $updateSql = "UPDATE MEMBER_DATA SET MEMBER_PASSWORD = ? WHERE MEMBER_ACCOUNT = ?";
            $updateStatement = $pdo->prepare($updateSql);
            $updateStatement->bindValue(1, $new_password);
            $updateStatement->bindValue(2, $email);
            $updateResult = $updateStatement->execute();

            if ($updateResult) {
                echo '0';
            } else {
                echo '1';
            }
        } else {
            echo '2'; // 只发送了邮件，没有提供验证码和新密码
        }
    } else {
        echo '发送邮件失败：' . $mail->ErrorInfo;
    }
 
} else {
    echo '1'; // 不存在匹配的MEMBER_ACCOUNT
}
?>
