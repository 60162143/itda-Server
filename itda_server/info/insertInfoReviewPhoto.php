<?php
require '../db/DBConfig.php';

    $reviewId = isset($_POST["reviewId"]) ? $_POST["reviewId"] : 0;     # 리뷰 고유 아이디
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;               # 유저 고유 아이디
    $fileName = isset($_POST["fileName"]) ? $_POST["fileName"] : "";    # 파일 명
    $fileEts = isset($_POST["fileEts"]) ? $_POST["fileEts"] : "";                # 파일 확장자
    $fileSize = isset($_POST["fileSize"]) ? $_POST["fileSize"] : 0;            # 파일 크기
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 리뷰 작성 시 업로드한 사진 파일 저장
        $sqlInsertFile = "INSERT INTO FILE
                                    ( FILE_PATH         # 파일 경로
                                    , FILE_SIZE         # 파일 크기
                                    , FILE_ETS          # 파일 확장자
                                    , FILE_NM           # 파일 명
                                    , REG_USER_ID )
                                VALUES
                                    ( '/ftpFileStorage/'
                                    , $fileSize
                                    , '$fileEts'
                                    , '$fileName'
                                    , $userId )";
        
        if(mysqli_query($conn, $sqlInsertFile)){
            $fileId = mysqli_insert_id($conn);      # Insert한 파일 고유 아이디
            
            # 리뷰 작성 시 업로드한 사진 정보 저장
            $sqlInsertReviewPhoto = "INSERT INTO REVIEW_PHOTO
                        ( RV_ID                     #리뷰 고유 아이디
                        , RV_PTO_IMG_ID     # 사진 파일 고유 아이디
                        , REG_USER_ID )
                    VALUES
                        ( $reviewId
                        , $fileId
                        , $userId )";
            
            mysqli_query($conn, $sqlInsertReviewPhoto);
            
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