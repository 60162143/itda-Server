<?php
    require '../db/DBConfig.php';
    
    $rvPhotoId = isset($_POST["rvPhotoId"]) ? $_POST["rvPhotoId"] : 0;  # 리뷰 작성 시 업로드한 사진 고유 아이디
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 리뷰 작성시 업로드한 사진 삭제
        $sqlDelete = "DELETE FROM REVIEW_PHOTO
                    WHERE RV_PTO_ID ='$rvPhotoId'";
        
        if(mysqli_query($conn, $sqlDelete)){
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