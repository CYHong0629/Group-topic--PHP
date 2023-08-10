<?php
    //會員註冊信箱是否已被註冊過

    include("Conn.php");

    $email = $_POST['email'];
    
    // 构建查询语句，检查是否存在匹配的MEMBER_ACCOUNT
    $sql = "SELECT COUNT(*) FROM MEMBER_DATA WHERE MEMBER_ACCOUNT = ?";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1, $email);
    $statement->execute();
    
    // 获取查询结果
    $result = $statement->fetchColumn();
    
    // 返回查询结果
    echo json_encode(array('exists' => $result > 0));
    
?>