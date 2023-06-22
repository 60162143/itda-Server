<?php
    require '../db/DBConfig.php';
    
    $reviewId = isset($_GET["reviewId"]) ? $_GET["reviewId"] : 0;   # 리뷰 고유 아이디
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        # 리뷰 댓글 조회
        $sql = "SELECT R_C.RV_CMT_ID    # 댓글 고유 아이디
                            , R_C.RV_ID             # 리뷰 고유 아이디
                            , R_C.USER_ID         # 유저 고유 아이디
                            , R.ST_ID                 # 가게 고유 아이디
                            , R_C.CMT_DET        # 댓글 내용
                            , DATE_FORMAT(R_C.REG_DATE, '%Y.%m.%d. %H:%i:%S') REG_DATE  # 댓글 작성일
                            , U.USER_NM           # 유저 명
                            , IFNULL(CONCAT(F.FILE_PATH, F.FILE_NM, '.', F.FILE_ETS), '/ftpFileStorage/noUser.png') USER_PRF_IMG_PATH   # 유저 프로필 이미지 경로
                    FROM REVIEW_COMMENT R_C
                            LEFT OUTER JOIN REVIEW R
                                ON R.RV_ID = R_C.RV_ID
                            LEFT OUTER JOIN USER U
                                ON U.USER_ID = R_C.USER_ID
                            LEFT OUTER JOIN FILE F
                                ON U.USER_PRF_IMG_ID = F.FILE_ID
                    WHERE R.RV_ID = $reviewId
                    ORDER BY R_C.REG_DATE";
        
        $result = mysqli_query($conn, $sql);
        
        $commentsArray = array();
        
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
            
            $commentsArray[$i] = array(
                "reviewCommentId" => $row['RV_CMT_ID']
                , "reviewId" => $row['RV_ID']
                , "userId" => $row['USER_ID']
                , "storeId" => $row['ST_ID']
                , "reviewCommentDetail" => $row['CMT_DET']
                , "reviewRegDate" => $row['REG_DATE']
                , "userName" => $row['USER_NM']
                , "userProfilePath" => $row['USER_PRF_IMG_PATH']
            );
            
        }
        
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("comment"=>$commentsArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
?>