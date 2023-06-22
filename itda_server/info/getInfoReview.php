<?php
    require '../db/DBConfig.php';
    
    $storeId = isset($_GET["storeId"]) ? $_GET["storeId"] : 0;  # 가게 고유 아이디
    $userId = isset($_GET["userId"]) ? $_GET["userId"] : 0;     # 유저 고유 아이디
    $loginUserId = isset($_GET["loginUserId"]) ? $_GET["loginUserId"] : 0;  # 로그인 되어있을 경우 로그인 유저 아이디
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        # 가게에 작성된 리뷰 목록 조회
        $sql = "SELECT R.RV_ID          # 리뷰 고유 아이디
                            , R.USER_ID       # 유저 고유 아이디
                            , U.USER_NM     # 유저 명
                            , R.ST_ID           # 가게 고유 아이디
                            , IFNULL(CONCAT(F.FILE_PATH, F.FILE_NM, '.', F.FILE_ETS), '/ftpFileStorage/noUser.png') USER_PRF_IMG_PATH   # 유저 프로필 이미지 경로
                            , R.RV_DET              # 리뷰 내용
                            , R.RV_SCO              # 리뷰 별점
                            , R.RV_HAT_CNT      # 리뷰 좋아요 수
                            , DATE_FORMAT(R.REG_DATE, '%Y.%m.%d. %H:%i:%S') REG_DATE    # 리뷰 작성일
                            , IFNULL(( SELECT COUNT(R_C.RV_CMT_ID)
                                                FROM REVIEW_COMMENT R_C
                                                WHERE R_C.RV_ID = R.RV_ID ), 0) RV_CMT_CNT              # 리뷰 댓글 수
                            , CASE
                                WHEN $loginUserId != 0
                                    THEN IFNULL(( SELECT COUNT(R_H.RV_HAT_ID)
                                                            FROM REVIEW_HEART R_H
                                                            WHERE R_H.RV_ID = R.RV_ID
                                                                AND R_H.USER_ID = $loginUserId), 0)
                                    ELSE 0
                                END RV_HAT_CLK_FLAG     # 유저가 로그인 되어있을 경우 좋아요 버튼을 눌렀는지 확인 Flag ( 누른 경우 : 1, 안눌렀거나 비로그인 시 : 0 )
                    FROM REVIEW R
                            LEFT OUTER JOIN USER U
                                ON U.USER_ID = R.USER_ID
                            LEFT OUTER JOIN FILE F
                                ON U.USER_PRF_IMG_ID = F.FILE_ID
                    WHERE 1 = 1";
        
        # 가게 고유 아이디 파라미터 값이 있을 경우
        if($storeId){
            $sql .= " AND R.ST_ID = $storeId";
        }
        
        # 유저 고유 아이디 파리미터 값이 있을 경우
        if($userId){
            $sql .= " AND R.USER_ID = $userId";
        }
        
        $sql .= " ORDER BY REG_DATE";   # 리뷰 작성 일자 내림차순 정렬
        
        $result = mysqli_query($conn, $sql);
        
        $reviewsArray = array();
        
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
            
            $reviewsArray[$i] = array(
                "reviewId" => $row['RV_ID']
                , "userId" => $row['USER_ID']
                , "userName" => $row['USER_NM']
                , "storeId" => $row['ST_ID']
                , "userProfilePath" => $row['USER_PRF_IMG_PATH']
                , "reviewDetail" => $row['RV_DET']
                , "reviewScore" => $row['RV_SCO']
                , "reviewHeartCount" => $row['RV_HAT_CNT']
                , "reviewRegDate" => $row['REG_DATE']
                , "reviewCommentCount" => $row['RV_CMT_CNT']
                , "reviewHeartIsClick" => $row['RV_HAT_CLK_FLAG']
            );
        }
        
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("review"=>$reviewsArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
?>