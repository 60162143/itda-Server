<?php
    require '../db/DBConfig.php';
    
    $bmkStId = isset($_POST["bmkStId"]) ? $_POST["bmkStId"] : 0;    # 찜한 가게 테이블 고유 아이디
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 찜한 가게 목록 Delete
        $sqlDelete = "DELETE FROM BOOKMARK_STORE
                    WHERE BMK_ST_ID ='$bmkStId'";
        
        if(mysqli_query($conn, $sqlDelete)){
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