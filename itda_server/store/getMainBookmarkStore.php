<?php
require '../db/DBConfig.php';

$userId = isset($_GET["userId"]) ? $_GET["userId"] : 0;     # 유저 고유 아이디

if($_SERVER["REQUEST_METHOD"] == "GET"){
    # 유저 찜한 가게 조회
    $sql = "SELECT S.ST_ID              # 가게 고유 아이디
                        , S.BMK_ST_ID       # 찜한 가게 테이블 고유 아이디
                FROM (SELECT T_S.*
                                    , B_S.BMK_ST_ID
                            FROM BOOKMARK_STORE B_S
                                LEFT OUTER JOIN STORE T_S
				                    ON T_S.ST_ID = B_S.ST_ID
                            WHERE B_S.USER_ID = $userId) S";
    
    $result = mysqli_query($conn, $sql);
    
    $bookmarkStoresArray = array();
    
    for($i = 0; $row = mysqli_fetch_Array($result); $i++){
        
        $bookmarkStoresArray[$i] = array(
            "storeId" => $row['ST_ID']
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