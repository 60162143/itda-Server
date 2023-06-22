<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;       # 유저 고유 아이디
    $birthday = isset($_POST["birthday"]) ? $_POST["birthday"] : "1900-01-01";  # 유저 생일
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 유저 생일 Update
        $sql = "UPDATE USER
                    SET USER_BIR_DATE = '$birthday'
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