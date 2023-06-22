<?php
require '../db/DBConfig.php';

$reviewId = isset($_POST["reviewId"]) ? $_POST["reviewId"] : 0;     # 리뷰 고유 아이디

$arr = array();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    # 리뷰에 작성된 댓글 삭제
    $sqlReviewCommentDelete = "DELETE FROM REVIEW_COMMENT
                                                WHERE RV_ID = $reviewId";
    
    mysqli_query($conn, $sqlReviewCommentDelete);
    
    # 리뷰 작성 시 업로드한 사진 파일 삭제
    $sqlReviewPhotoFileDelete = "DELETE FROM FILE
                                                WHERE FILE_ID IN ( SELECT RV_PTO_IMG_ID
                                                                                FROM REVIEW_PHOTO
                                                                                WHERE RV_ID = $reviewId )";
    
    mysqli_query($conn, $sqlReviewPhotoFileDelete);
    
    # 리뷰 작성 시 업로드한 사진 정보 삭제
    $sqlReviewPhotoDelete = "DELETE FROM REVIEW_PHOTO
                                            WHERE RV_ID = $reviewId";
    
    mysqli_query($conn, $sqlReviewPhotoDelete);
    
    # 작성한 리뷰 삭제
    $sqlReviewDelete = "DELETE FROM REVIEW
                                    WHERE RV_ID = $reviewId";
    
    if(mysqli_query($conn, $sqlReviewDelete)){
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