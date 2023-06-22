<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_GET["userId"]) ? $_GET["userId"] : "";                # 유저 고유 아이디
    $latitude = isset($_GET["latitude"]) ? $_GET["latitude"] : 0;           # 유저 현재 위치 위도 값
    $longitude = isset($_GET["longitude"]) ? $_GET["longitude"] : 0;    # 유저 현재 위치 경도 값

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        # 유저 찜한 가게 목록 조회
        $sql = "SELECT S.ST_ID          # 가게 고유 아이디
                         , S.ST_NM             # 가게 명
                         , S.ST_LAT            # 가게 위도 값
                         , S.ST_LON           # 가게 경도 값
                         , S.ST_INFO          # 가게 간단 소개
                         , S.ST_CAT_ID      # 가게 카테고리 고유 아이디
                         , IFNULL(CONCAT(F.FILE_PATH, F.FILE_NM, '.', F.FILE_ETS), '/ftpFileStorage/noImage.png') ST_TMN_IMG_PATH   # 가게 썸네일 이미지 경로
                         , IFNULL(( SELECT SUM(R.RV_SCO) / COUNT(R.RV_SCO)
			                             FROM REVIEW R
			                             WHERE R.ST_ID = S.ST_ID ), 0) ST_SCO        # 가게 별점
                        , IFNULL(( SELECT GROUP_CONCAT(HASH_NM SEPARATOR ' ')
                                        FROM HASHTAG H
                                        WHERE H.ST_ID = S.ST_ID ), '') ST_HASH      # 가게 해시태그
                         , ( 6371 * acos(cos(radians($latitude)) * cos(radians(S.ST_LAT)) * cos(radians(S.ST_LON) 
                            - radians($longitude)) + sin(radians($latitude)) * sin(radians(S.ST_LAT)))) AS DIST         # DIST는 두 점 사이의 거리를 공식을 이용하여 계산
                        , S.BMK_ST_ID
                    FROM ( SELECT T_S.*
                                        , B_S.BMK_ST_ID 
                                FROM BOOKMARK_STORE B_S
				                LEFT OUTER JOIN STORE T_S
				                    ON T_S.ST_ID = B_S.ST_ID
                                WHERE B_S.USER_ID = '$userId' ) S
                    LEFT OUTER JOIN FILE F
                        ON S.ST_TMN_IMG_ID = F.FILE_ID
                ORDER BY DIST";
        
        $result = mysqli_query($conn, $sql);
        
        $bookmarkStoresArray = array();
        
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
            
            $bookmarkStoresArray[$i] = array(
                "storeId" => $row['ST_ID']
                , "storeName" => $row['ST_NM']
                , "storeLatitude" => $row['ST_LAT']
                , "storeLongitude" => $row['ST_LON']
                , "storeInfo" => $row['ST_INFO']
                , "storeThumbnailPath" => $row['ST_TMN_IMG_PATH']
                , "storeScore" => $row['ST_SCO']
                , "storeHashTag" => $row['ST_HASH']
                , "bookmarkStoreId" => $row['BMK_ST_ID']
            );
            
        }
        
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("bookmarkStore"=>$bookmarkStoresArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
    
?>