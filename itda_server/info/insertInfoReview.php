<?php
require '../db/DBConfig.php';

    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;       # 유저 고유 아이디
    $storeId = isset($_POST["storeId"]) ? $_POST["storeId"] : 0;    # 가게 고유 아이디
    $reviewContent = isset($_POST["reviewContent"]) ? $_POST["reviewContent"] : "";     # 리뷰 내용
    $reviewScore = isset($_POST["reviewScore"]) ? $_POST["reviewScore"] : 0;        # 리뷰 별점
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        # 작성한 리뷰 데이터 Insert
        $sqlInsert = "INSERT INTO REVIEW
                                ( USER_ID       # 유저 고유 아이디
                                , ST_ID           # 가게 고유 아이디
                                , RV_DET        # 리뷰 내용
                                , RV_SCO       # 리뷰 별점
                                , REG_USER_ID )
                            VALUES
                                ( $userId
                                , $storeId
                                , '$reviewContent'
                                , $reviewScore
                                , $userId )";
        
        if(mysqli_query($conn, $sqlInsert)){
            $reviewId = mysqli_insert_id($conn);    # Insert된 리뷰 고유 아이디
            
            $arr["reviewId"] = $reviewId;
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