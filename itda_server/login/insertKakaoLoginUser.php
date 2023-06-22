<?php
    require '../db/DBConfig.php';
    
    $email = isset($_POST["email"]) ? $_POST["email"] : "";                 # 유저 이메일
    $name = isset($_POST["name"]) ? $_POST["name"] : "";                # 유저 명
    $birthday = isset($_POST["birthday"]) ? $_POST["birthday"] : "";    # 유저 생일
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 카카오 회원가입 데이터 Insert
        $sqlUserInsert = "INSERT INTO USER
                        ( USER_EML                   # 유저 이메일
                        , USER_PWD                  # 유저 비밀번호
                        , USER_PRF_IMG_ID       # 유저 프로필 이미지 고유 아이디
                        , USER_NUM                  # 유저 연락처
                        , USER_NM                    # 유저 명
                        , USER_BIR_DATE         # 유저 생일
                        , USER_LOGIN_FLAG     # 유저 로그인 방식 Flag ( 일반 로그인 : noraml, 카카오 로인 : kakao )
                        , REG_USER_ID )
                    VALUES
                        ( '$email'
                        , ''
                        , 0
                        , ''
                        , '$name'
                        , '$birthday'
                        , 1
                        , 1 )";
        
        if(mysqli_query($conn, $sqlUserInsert)){
            $userId = mysqli_insert_id($conn);  # Insert된 유저 고유 아이디
           
            $arr["userId"] = $userId;   # 유저 고유 아이디
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