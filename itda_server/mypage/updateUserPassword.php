<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;   # 유저 고유 아이디
    $password = isset($_POST["password"]) ? $_POST["password"] : "";    # 유저 비밀번호
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 유저 비밀번호 Update
        $sql = "UPDATE USER
                    SET USER_PWD = '$password'
                    WHERE USER_ID = $userId";
        
        if(mysqli_query($conn, $sql)){
            $arr["success"] = "1";
        }else{
            $arr["success"] = "-1";
        }
    }else{
        $arr["success"] = "error";
    }
    
    header('Content-Type: application/json; charset=utf8');
    $json = json_encode($arr, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    echo $json;
?>