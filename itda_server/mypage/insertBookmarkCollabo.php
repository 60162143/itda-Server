<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;               # 유저 고유 아이디
    $collaboId = isset($_POST["collaboId"]) ? $_POST["collaboId"] : 0;     # 협업 고유 아이디
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 유저 찜한 협업 목록 Insert
        $sqlInsert = "INSERT INTO BOOKMARK_COLLABORATION
                        ( USER_ID       # 유저 고유 아이디
                        , COB_ID )      # 협업 고유 아이디
                    VALUES
                        ( $userId
                        , $collaboId )";
        
        if(mysqli_query($conn, $sqlInsert)){
            $bmkCobId = mysqli_insert_id($conn);    # Insert된 찜한 협업 테이블 고유 아이디
            
            $arr["bmkCobId"] = $bmkCobId;   # 찜한 협업 테이블 고유 아이디
            $arr["success"] = "1";
        }else{
            $arr["success"] = "-1";
        }
    }else{
        $arr["success"] = "error";
    }
    
    header('Content-Type: application/json; charset=utf8');
    $json = json_encode($arr, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    echo $json;
?>