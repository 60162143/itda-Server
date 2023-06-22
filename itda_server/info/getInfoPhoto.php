<?php
    require '../db/DBConfig.php';
    
    $storeId = isset($_GET["storeId"]) ? $_GET["storeId"] : 0;  # 가게 고유 아이디
    $userId = isset($_GET["userId"]) ? $_GET["userId"] : 0; # 유저 고유 아이디
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        # 가게 업로드 된 사진 조회
        $sql = "SELECT R_P.RV_PTO_ID    # 사진 고유 아이디
                        		, R.USER_ID         # 유저 고유 아이디
                                , U.USER_NM       # 유저 명
                                , CONCAT(F.FILE_PATH, F.FILE_NM, '.', F.FILE_ETS) RV_PTO_IMG_PATH   # 사진 이미지 경로
                                , R_P.RV_ID     # 리뷰 고유 아이디
                                , R.RV_DET      # 리뷰 내용
                                , R.RV_SCO     # 리뷰 별점
                    FROM REVIEW_PHOTO R_P
                        LEFT OUTER JOIN REVIEW R
                            ON R_P.RV_ID = R.RV_ID
                        LEFT OUTER JOIN USER U
                            ON R.USER_ID = U.USER_ID
                        LEFT OUTER JOIN FILE F
                            ON R_P.RV_PTO_IMG_ID = F.FILE_ID
                    WHERE 1 = 1";
        
        # 가게 고유 아이디 파라미터 값이 있을 경우
        if($storeId){
            $sql .= " AND R.ST_ID = $storeId";
        }
        
        # 유저 고유 아이디 파리미터 값이 있을 경우
        if($userId){
            $sql .= " AND R.USER_ID = $userId";
        }
        
        $result = mysqli_query($conn, $sql);
        
        $photosArray = array();
        
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
            
            $photosArray[$i] = array(
                "photoId" => $row['RV_PTO_ID']
                , "userId" => $row['USER_ID']
                , "userName" => $row['USER_NM']
                , "photoImagePath" => $row['RV_PTO_IMG_PATH']
                , "reviewId" => $row['RV_ID']
                , "reviewDetail" => $row['RV_DET']
                , "reviewScore" => $row['RV_SCO']
            );
            
        }
        
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("photo"=>$photosArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
?>