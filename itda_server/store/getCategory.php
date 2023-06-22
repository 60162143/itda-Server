<?php
    require '../db/DBConfig.php';

    # 카테고리 정보 조회
    $sql = "SELECT C.CAT_ID     # 카테고리 고유 아이디
                         , C.CAT_NM     # 카테고리 명
                         , CONCAT(F.FILE_PATH, F.FILE_NM, '.', F.FILE_ETS) IMAGE_PATH   # 카테고리 이미지 경로
                FROM CATEGORY C
                        , FILE F 
                WHERE C.CAT_IMG_ID = F.FILE_ID";
    $result = mysqli_query($conn, $sql);

    $categoriesArray = array();

    for($i = 0; $row = mysqli_fetch_Array($result); $i++){
        
        $categoriesArray[$i] = array(
            "categoryId" => $row['CAT_ID']
            , "categoryNm" => $row["CAT_NM"]
            , "imagePath" => $row["IMAGE_PATH"]
        );
        
    }

    header('Content-Type: application/json; charset=utf8');
    $json = json_encode(array("category"=>$categoriesArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    echo $json;
?>