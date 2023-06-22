<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_GET["userId"]) ? $_GET["userId"] : "";    # 유저 고유 아이디

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        # 유저 찜한 협업 목록 조회
        $sql = "SELECT B_C.BMK_COB_ID                    # 찜한 협업 테이블 고유 아이디
                            , PRV_S.ST_ID AS PRV_ST_ID      # 앞 가게 고유 아이디
                            , PRV_S.ST_NM AS PRV_ST_NM   # 앞 가게 명
                            , IFNULL(CONCAT(PRV_F.FILE_PATH, PRV_F.FILE_NM, '.', PRV_F.FILE_ETS), '/ftpFileStorage/noImage.png') AS PRV_ST_TMN_IMG_PATH     # 앞 가게 썸네일 이미지 경로
                            , C.PRV_DIS_CON                             # 앞 가게 할인 조건
                            , POST_S.ST_ID AS POST_ST_ID       # 뒷 가게 고유 아이디
                            , POST_S.ST_NM AS POST_ST_NM     # 뒷 가게 명
                            , IFNULL(CONCAT(POST_F.FILE_PATH, POST_F.FILE_NM, '.', POST_F.FILE_ETS), '/ftpFileStorage/noImage.png') AS POST_ST_TMN_IMG_PATH     # 뒷 가게 썸네일 이미지 경로
                            , C.POST_DIS_RATE                           # 뒷 가게 할인 율
                            , ( 6371 * acos(cos(radians(PRV_S.ST_LAT)) * cos(radians(POST_S.ST_LAT)) * cos(radians(POST_S.ST_LON) 
                                - radians(PRV_S.ST_LON)) + sin(radians(PRV_S.ST_LAT)) * sin(radians(POST_S.ST_LAT)))) AS DIST   # DIST는 두 점 사이의 거리를 공식을 이용하여 계산
                    FROM BOOKMARK_COLLABORATION B_C
                    LEFT OUTER JOIN COLLABORATION C
                        ON B_C.COB_ID = C.COB_ID
                    LEFT OUTER JOIN STORE PRV_S
                        ON PRV_S.ST_ID = C.PRV_ST_ID
                    LEFT OUTER JOIN FILE PRV_F
                        ON PRV_S.ST_TMN_IMG_ID = PRV_F.FILE_ID
                    LEFT OUTER JOIN STORE POST_S
                        ON POST_S.ST_ID = C.POST_ST_ID
                    LEFT OUTER JOIN FILE POST_F
                        ON POST_S.ST_TMN_IMG_ID = POST_F.FILE_ID
                    WHERE USER_ID = '$userId'
                    ORDER BY DIST";
        
        $result = mysqli_query($conn, $sql);
        
        $bookmarkCollabosArray = array();
        
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
            
            $bookmarkCollabosArray[$i] = array(
                "bookmarkCollaboId" => $row['BMK_COB_ID']
                , "prvStoreId" => $row['PRV_ST_ID']
                , "prvStoreName" => $row['PRV_ST_NM']
                , "prvStoreImagePath" => $row['PRV_ST_TMN_IMG_PATH']
                , "prvDiscountCondition" => $row['PRV_DIS_CON']
                , "postStoreId" => $row['POST_ST_ID']
                , "postStoreName" => $row['POST_ST_NM']
                , "postStoreImagePath" => $row['POST_ST_TMN_IMG_PATH']
                , "postDiscountRate" => $row['POST_DIS_RATE']
                , "distance" => $row['DIST']
            );
            
        }
        
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("bookmarkCollabo"=>$bookmarkCollabosArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
    
?>