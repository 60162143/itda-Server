<?php
    require '../db/DBConfig.php';
    
    $bmkCobId = isset($_POST["bmkCobId"]) ? $_POST["bmkCobId"] : 0;     # 찜한 협업 테이블 고유 아이디
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 찜한 협업 목록 Delete
        $sqlDelete = "DELETE FROM BOOKMARK_COLLABORATION
                    WHERE BMK_COB_ID ='$bmkCobId'";
        
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