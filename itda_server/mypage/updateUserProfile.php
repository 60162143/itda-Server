<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;              # 유저 고유 아이디
    $fileName = isset($_POST["fileName"]) ? $_POST["fileName"] : "";    # 파일 명
    $fileEts = isset($_POST["fileEts"]) ? $_POST["fileEts"] : "";               # 파일 확장자
    $fileSize = isset($_POST["fileSize"]) ? $_POST["fileSize"] : 0;           # 파일 크기
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 기존 유저 프로필 이미지 파일 Delete
        $sqlDelete = "DELETE FROM FILE
                            WHERE FILE_ID = (SELECT USER_PRF_IMG_ID
                                                        FROM USER 
                                                        WHERE USER_ID = $userId)";
        
        mysqli_query($conn, $sqlDelete);
        
        # 새로은 유저 프로필 이미지 파일 Insert
        $sqlInsert = "INSERT INTO FILE
                        ( FILE_PATH        # 파일 경로
                        , FILE_SIZE         # 파일 크기
                        , FILE_ETS          # 파일 확장자
                        , FILE_NM           # 파일 명
                        , REG_USER_ID )
                    VALUES
                        ( '/ftpFileStorage/'
                        , '$fileSize'
                        , '$fileEts'
                        , '$fileName'
                        , '$userId' )";
        
        if(mysqli_query($conn, $sqlInsert)){
            $fileId = mysqli_insert_id($conn);      # Insert된 파일 고유 아이디
            
            # 유저 데이터 Update
            $sqlUpdate = "UPDATE USER
                    SET USER_PRF_IMG_ID = $fileId
                    WHERE USER_ID = $userId";
            
            mysqli_query($conn, $sqlUpdate);
            
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