<?php
    require '../db/DBConfig.php';
    
    $storeId = isset($_GET["storeId"]) ? $_GET["storeId"] : 0;                    # 가게 고유 아이디
    $categoryId = isset($_GET["categoryId"]) ? $_GET["categoryId"] : 0;     # 카테고리 고유 아이디
    $schText = isset($_GET["schText"]) ? $_GET["schText"] : "";                 # 검색어
    
    $sql = "SELECT S.ST_ID      # 가게 고유 아이디
                         , S.ST_NM      # 가게 명
                         , S.ST_ADR     # 가게 주소
                         , S.ST_DTL     # 가게 간단 제공 서비스
                         , S.ST_FAC     # 가게 제공 시설 여부
                         , S.ST_LAT     # 가게 위도 값
                         , S.ST_LON     # 가게 경도 값
                         , IFNULL(S.ST_NUM, '') AS ST_NUM   # 가게 연락처
                         , S.ST_INFO    # 가게 간단 소개
                         , S.ST_CAT_ID      # 가게 카테고리 고유 아이디
                         , IFNULL(CONCAT(F.FILE_PATH, F.FILE_NM, '.', F.FILE_ETS), '/ftpFileStorage/noImage.png') ST_TMN_IMG_PATH   # 가게 썸네일 이미지 경로
                         , IFNULL(( SELECT SUM(R.RV_SCO) / COUNT(R.RV_SCO)
			                             FROM REVIEW R 
			                             WHERE R.ST_ID = S.ST_ID ), 0) ST_SCO    # 가게 별점
                        , IFNULL(( SELECT GROUP_CONCAT(WORK_TIME SEPARATOR '\\n') 
                                        FROM STORE_WORKING_TIME S_W
                                        WHERE S_W.ST_ID = S.ST_ID ), '') ST_WORK_TIME       # 가게 운영 시간
                        , IFNULL(( SELECT GROUP_CONCAT(HASH_NM SEPARATOR ' ')
                                        FROM HASHTAG H
                                        WHERE H.ST_ID = S.ST_ID ), '') ST_HASH      # 가게 해시태그
                        , IFNULL(( SELECT COUNT(R.RV_ID)
                                        FROM REVIEW R
                                        WHERE R.ST_ID = S.ST_ID ), 0) ST_RV_CNT     # 가게 리뷰 수
                FROM STORE S 
                    LEFT OUTER JOIN FILE F 
                        ON S.ST_TMN_IMG_ID = F.FILE_ID
                WHERE 1 = 1";
    
    # 검색어 파라미터 값이 있을 경우
    if($schText){
        $sql .= " AND S.ST_NM LIKE '%$schText%'";
    }
    
    # 가게 고유 아이디 파라미터 값이 있을 경우
    if($storeId){
        $sql .= " AND S.ST_ID = $storeId";
    }
    
    # 카테고리 고유 아이디 파라미터 값이 있을 경우
    if($categoryId){
        $sql .= " AND S.ST_CAT_ID = $categoryId";
    }
    
    $result = mysqli_query($conn, $sql);

    $storesArray = array();

    for($i = 0; $row = mysqli_fetch_Array($result); $i++){

        $storesArray[$i] = array(
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
            , "storeHashTag" => $row['ST_HASH']
            , "storeReviewCount" => $row['ST_RV_CNT']
        );
        
    }

    header('Content-Type: application/json; charset=utf8');
    $json = json_encode(array("store"=>$storesArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    echo $json;
?>