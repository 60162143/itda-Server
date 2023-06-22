<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;   # 유저 고유 아이디
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 유저 프로필 이미지 Delete
        $sqlDelete = "DELETE FROM FILE
                            WHERE FILE_ID = (SELECT USER_PRF_IMG_ID
                                                            FROM USER 
                                                            WHERE USER_ID = $userId)";
        
        mysqli_query($conn, $sqlDelete);
        
        # 유저 프로필 이미지 고유 아이디 갱신
        $sqlUpdate = "UPDATE USER
                    SET USER_PRF_IMG_ID = 0
                    WHERE USER_ID = $userId";
        
        if(mysqli_query($conn, $sqlUpdate)){
            $arr["noProfilePath"] = "/ftpFileStorage/noUser.png";   # 유저 프로필 기본 이미지 경로
            
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