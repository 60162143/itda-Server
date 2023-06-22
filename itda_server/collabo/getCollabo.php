<?php
    require '../db/DBConfig.php';
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        # 협업 목록 조회
        $sql = "SELECT C.COB_ID               # 협업 고유 아이디
                            , C.PRV_ST_ID           # 앞 가게 고유 아이디
                            , C.POST_ST_ID         # 뒷 가게 고유 아이디
                            , C.PRV_DIS_CON      # 앞 가게 할인 조건
                            , C.POST_DIS_RATE   # 뒷 가게 할인 율
                            , S_PRV.ST_NM PRV_ST_NM        #앞 가게 명
                            , S_POST.ST_NM POST_ST_NM    # 뒷 가게 명
                            , IFNULL(CONCAT(F_PRV.FILE_PATH, F_PRV.FILE_NM, '.', F_PRV.FILE_ETS), '/ftpFileStorage/noImage.png') PRV_ST_TMN_IMG_PATH    # 알 가게 프로필 이미지
                            , IFNULL(CONCAT(F_POST.FILE_PATH, F_POST.FILE_NM, '.', F_POST.FILE_ETS), '/ftpFileStorage/noImage.png') POST_ST_TMN_IMG_PATH    # 뒷 가게 프로필 이미지
                            , ROUND(( 6371 * acos(cos(radians(S_PRV.ST_LAT)) * cos(radians(S_POST.ST_LAT)) * cos(radians(S_POST.ST_LON) 
                                - radians(S_PRV.ST_LON)) + sin(radians(S_PRV.ST_LAT)) * sin(radians(S_POST.ST_LAT)))), 2) AS DIST   # 가게 간 거리
                    FROM COLLABORATION C
                        LEFT OUTER JOIN STORE S_PRV
                        	ON S_PRV.ST_ID = C.PRV_ST_ID
                        LEFT OUTER JOIN STORE S_POST
                        	ON S_POST.ST_ID = C.POST_ST_ID
                        LEFT OUTER JOIN FILE F_PRV
                            ON S_PRV.ST_TMN_IMG_ID = F_PRV.FILE_ID
                        LEFT OUTER JOIN FILE F_POST
                            ON S_POST.ST_TMN_IMG_ID = F_POST.FILE_ID
                    WHERE NOW() >= C.START_DATE
                        AND NOW() <= C.END_DATE";
        
        $result = mysqli_query($conn, $sql);
    
        $collaboesArray = array();
    
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
    
            $collaboesArray[$i] = array(
                "collaboId" => $row['COB_ID']
                , "prvStoreId" => $row['PRV_ST_ID']
                , "postStoreId" => $row['POST_ST_ID']
                , "prvDiscountCondition" => $row['PRV_DIS_CON']
                , "postDiscountRate" => $row['POST_DIS_RATE']
                , "prvStoreName" => $row['PRV_ST_NM']
                , "postStoreName" => $row['POST_ST_NM']
                , "prvStoreImagePath" => $row['PRV_ST_TMN_IMG_PATH']
                , "postStoreImagePath" => $row['POST_ST_TMN_IMG_PATH']
                , "collaboDistance" => $row['DIST']
            );
            
        }
    
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("collabo"=>$collaboesArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
?>