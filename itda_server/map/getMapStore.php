<?php
    require '../db/DBConfig.php';
    
    $schText = isset($_GET["schText"]) ? $_GET["schText"] : "";             # 검색어
    $latitude = isset($_GET["latitude"]) ? $_GET["latitude"] : "";              # 유저 위치 위도 값
    $longitude = isset($_GET["longitude"]) ? $_GET["longitude"] : "";       # 유저 위치 경도 값

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        # 지도 화면 내 가게 데이터 조회
        $sql = "SELECT S.ST_ID          # 가게 고유 아이디
                         , S.ST_NM             # 가게 명
                         , S.ST_LAT            # 가게 위도 값
                         , S.ST_LON           # 가게 경도 값
                         , S.ST_INFO          # 가게 간단 소개
                         , S.ST_CAT_ID      # 가게 카테고리 고유 아이디
                         , IFNULL(CONCAT(F.FILE_PATH, F.FILE_NM, '.', F.FILE_ETS), '/ftpFileStorage/noImage.png') ST_TMN_IMG_PATH   # 가게 썸네일 이미지 경로
                         , IFNULL(( SELECT SUM(R.RV_SCO) / COUNT(R.RV_SCO)
			                             FROM REVIEW R
			                             WHERE R.ST_ID = S.ST_ID ), 0) ST_SCO    # 가게 별점
                        , IFNULL(( SELECT GROUP_CONCAT(HASH_NM SEPARATOR ' ')
                                        FROM HASHTAG H
                                        WHERE H.ST_ID = S.ST_ID ), '') ST_HASH  # 가게 해시태그
                         , ( 6371 * acos(cos(radians($latitude)) * cos(radians(S.ST_LAT)) * cos(radians(S.ST_LON) 
                            - radians($longitude)) + sin(radians($latitude)) * sin(radians(S.ST_LAT)))) AS DIST     # DIST 는 두 점 사이의 거리를 공식을 이용해서 계산
                FROM STORE S
                    LEFT OUTER JOIN FILE F
                        ON S.ST_TMN_IMG_ID = F.FILE_ID
                WHERE S.ST_NM LIKE '%$schText%'
                ORDER BY DIST
                LIMIT 100";
        
        $result = mysqli_query($conn, $sql);
        
        $storesArray = array();
        
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
            
            $storesArray[$i] = array(
                "storeId" => $row['ST_ID']
                , "storeName" => $row['ST_NM']
                , "storeLatitude" => $row['ST_LAT']
                , "storeLongitude" => $row['ST_LON']
                , "storeInfo" => $row['ST_INFO']
                , "storeThumbnailPath" => $row['ST_TMN_IMG_PATH']
                , "storeScore" => $row['ST_SCO']
                , "storeHashTag" => $row['ST_HASH']
            );
            
        }
        
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("store"=>$storesArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
?>