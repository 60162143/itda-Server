<?php
require '../db/DBConfig.php';

    $reviewId = isset($_POST["reviewId"]) ? $_POST["reviewId"] : 0;     # 리뷰 고유 아이디
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;               # 유저 고유 아이디
    $flag = isset($_POST["flag"]) ? $_POST["flag"] : 0;                           # 리뷰 좋아요 클릭 Flag ( 추가 : +1, 삭제 : -1 )
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $sql = "";
        
        if($flag == 1){
            # 리뷰 좋아요 데이터 Insert
            $sql = "INSERT INTO REVIEW_HEART
                        ( RV_ID
                        , USER_ID )
                    VALUES
                        ( $reviewId
                        , $userId )";
        }else if($flag == -1){
            # 리뷰 좋아요 데이터 Delete
            $sql = "DELETE FROM REVIEW_HEART
                    WHERE RV_ID =$reviewId
                    AND USER_ID = $userId";
        }
        
        if(mysqli_query($conn, $sql)){
            # 리뷰 좋아요 수 갱신
            $updateSql = "UPDATE REVIEW
                    SET RV_HAT_CNT = RV_HAT_CNT + $flag
                    WHERE RV_ID = $reviewId";
            
            if(mysqli_query($conn, $updateSql)){
                $arr["success"] = "1";
            }else{
                $arr["success"] = "-1";
            }
            
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