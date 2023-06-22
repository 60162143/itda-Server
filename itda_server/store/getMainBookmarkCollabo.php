<?php
require '../db/DBConfig.php';

$userId = isset($_GET["userId"]) ? $_GET["userId"] : 0;     # 유저 고유 아이디

if($_SERVER["REQUEST_METHOD"] == "GET"){
    # 유저 찜한 협업 목록 간단 데이터 조회
    $sql = "SELECT BMK_COB_ID   # 찜한 협업 목록 테이블 고유 아이디
                        , COB_ID            # 협업 목록 고유 아이디
                FROM BOOKMARK_COLLABORATION
                WHERE USER_ID = $userId";
    
    $result = mysqli_query($conn, $sql);
    
    $bookmarkCollabosArray = array();
    
    for($i = 0; $row = mysqli_fetch_Array($result); $i++){
        
        $bookmarkCollabosArray[$i] = array(
            "bookmarkCollaboId" => $row['BMK_COB_ID']
            , "collaboId" => $row['COB_ID']
        );
        
    }
    
    header('Content-Type: application/json; charset=utf8');
    $json = json_encode(array("bookmarkCollabo"=>$bookmarkCollabosArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    echo $json;
}else{
    echo "error";
}

?>