<?php
require '../db/DBConfig.php';
    
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;               # 유저 고유 아이디
    $reviewId = isset($_POST["reviewId"]) ? $_POST["reviewId"] : 0;      # 리뷰 고유 아이디
    $comment = isset($_POST["comment"]) ? $_POST["comment"] : "";  # 댓글 내용
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 작성한 리뷰 댓글 데이터 Insert
        $sqlInsert = "INSERT INTO REVIEW_COMMENT
                                ( RV_ID            # 리뷰 고유 아이디
                                , USER_ID        # 유저 고유 아이디
                                , CMT_DET )     # 댓글 내용
                            VALUES
                                ( $reviewId
                                , $userId
                                , '$comment' )";
        
        if(mysqli_query($conn, $sqlInsert)){
            $reviewCommentId = mysqli_insert_id($conn);     # Insert한 댓글 고유 아이디
            
            $sqlDate = "SELECT DATE_FORMAT(REG_DATE, '%Y.%m.%d. %H:%i:%S') REG_DATE     # 댓글 작성일
                                FROM REVIEW_COMMENT
                                WHERE RV_CMT_ID = $reviewCommentId";
    
            $result = mysqli_query($conn, $sqlDate);
            
            for($i = 0; $row = mysqli_fetch_Array($result); $i++){
                $arr["reviewCommentRegDate"] = $row['REG_DATE'];    # 댓글 작성이ㄹ
            }
            
            $arr["reviewCommentId"] = $reviewCommentId; # 댓글 고유 아이디
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