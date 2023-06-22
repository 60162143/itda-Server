<?php
    require '../db/DBConfig.php';
    
    $storeId = isset($_GET["storeId"]) ? $_GET["storeId"] : 0;      # 가게 고유 아이디
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        # 협업 가게 정보 조회
        $sql = "SELECT S.ST_ID           # 가게 고유 아이디
                            , S.ST_NM           # 가게 명
                            , S.ST_ADR         # 가게 주소
                            , S.ST_DTL         # 가게 간단 제공 서비스
                            , S.ST_FAC         # 가게 제공 시설 여부
                            , S.ST_LAT         # 가게 위도
                            , S.ST_LON        # 가게 경도
                            , S.ST_NUM        # 가게 연락처
                            , S.ST_INFO       # 가게 간단 소개
                            , S.ST_CAT_ID   # 카테고리 고유 아이디
                            , IFNULL(CONCAT(F.FILE_PATH, F.FILE_NM, '.', F.FILE_ETS), '/ftpFileStorage/noImage.png') ST_TMN_IMG_PATH    # 가게 썸네일 이미지
                            , IFNULL(( SELECT SUM(R.RV_SCO) / COUNT(R.RV_SCO)
                                            FROM REVIEW R 
                                            WHERE R.ST_ID = S.ST_ID ), 0) ST_SCO    # 가게 별점
                            , ( SELECT GROUP_CONCAT(WORK_TIME SEPARATOR '&&') 
                                FROM STORE_WORKING_TIME S_W
                                WHERE S_W.ST_ID = S.ST_ID ) ST_WORK_TIME    # 가게 운영 시간
                            , C.COB_ID                # 협업 고유 아이디
                            , C.POST_ST_ID         # 뒷 가게 고유 아이디
                            , C.PRV_DIS_CON      # 앞 가게 할인 조건
                            , C.POST_DIS_RATE  # 뒷 가게 할인 율
                    FROM STORE S 
                        LEFT OUTER JOIN FILE F 
                            ON S.ST_TMN_IMG_ID = F.FILE_ID
                        LEFT OUTER JOIN COLLABORATION C
                        	ON S.ST_ID = C.POST_ST_ID
                    WHERE NOW() >= C.START_DATE
                        AND NOW() <= C.END_DATE
                        AND C.PRV_ST_ID = $storeId";
        
        $result = mysqli_query($conn, $sql);
    
        $collaboesArray = array();
    
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
    
            $collaboesArray[$i] = array(
                "storeId" => $row['ST_ID']
                , "storeName" => $row['ST_NM']
                , "storeAddress" => $row['ST_ADR']
                , "storeDetail" => $row['ST_DTL']
                , "storeFacility" => $row['ST_FAC']
                , "storeLatitude" => $row['ST_LAT']
                , "storeLongitude" => $row['ST_LON']
                , "storeNumber" => $row['ST_NUM']
                , "storeInfo" => $row['ST_INFO']
                , "storeCategoryId" => $row['ST_CAT_ID']
                , "storeThumbnailPath" => $row['ST_TMN_IMG_PATH']
                , "storeScore" => $row['ST_SCO']
                , "storeWorkingTime" => $row['ST_WORK_TIME']
                , "collaboId" => $row['COB_ID']
                , "collaboStoreId" => $row['POST_ST_ID']
                , "collaboDiscountCondition" => $row['PRV_DIS_CON']
                , "collaboDiscountRate" => $row['POST_DIS_RATE']
            );
        }
    
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("collabo"=>$collaboesArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
?>