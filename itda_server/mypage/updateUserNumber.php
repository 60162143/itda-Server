<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;   # 유저 명
    $number = isset($_POST["number"]) ? $_POST["number"] : "";  # 유저 연락처
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 유저 연락처 Update
        $sql = "UPDATE USER
                    SET USER_NUM = '$number'
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