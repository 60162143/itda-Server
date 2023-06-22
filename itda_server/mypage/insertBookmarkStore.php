<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;       # 유저 고유 아이디
    $storeId = isset($_POST["storeId"]) ? $_POST["storeId"] : 0;    # 가게 고유 아이디
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 유저 찜한 가게 정보 Insert
        $sqlInsert = "INSERT INTO BOOKMARK_STORE
                        ( USER_ID       # 유저 고유 아이디
                        , ST_ID )         # 가게 고유 아이디
                    VALUES
                        ( '$userId'
                        , '$storeId' )";
        
        if(mysqli_query($conn, $sqlInsert)){
            $bmkStId = mysqli_insert_id($conn);     # Insert된 찜한 가게 고유 아이디
            
            $arr["bmkStId"] = $bmkStId;     # 찜한 가게 고유 아이디
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