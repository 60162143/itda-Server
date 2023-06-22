<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;                                 # 유저 고유 아이디
    $userName = isset($_POST["userName"]) ? $_POST["userName"] : "";                # 유저 명
    $userNumber = isset($_POST["userNumber"]) ? $_POST["userNumber"] : "";      # 유저 연락처
    $userBirthday = isset($_POST["userBirthday"]) ? $_POST["userBirthday"] : "";    # 유저 생일
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 회원가입 유저 정보 갱신
        $sqlUpdate = "UPDATE USER
                    SET USER_NUM = '$userNumber'            # 유저 연락처
                        , USER_NM = '$userName'                 # 유저 명
                        , USER_BIR_DATE = '$userBirthday'   # 유저 생일
                    WHERE USER_ID = $userId";
        
        if(mysqli_query($conn, $sqlUpdate)){
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