<?php
    require '../db/DBConfig.php';
    
    $email = isset($_POST["email"]) ? $_POST["email"] : "";                     # 유저 이메일
    $password = isset($_POST["password"]) ? $_POST["password"] : "";    # 유저 비밀번호
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 일반 회원가입 데이터 Insert
        $sqlInsert = "INSERT INTO USER
                        ( USER_EML          # 유저 이메일
                        , USER_PWD )    # 유저 비밀번호
                    VALUES
                        ( '$email'
                        , '$password' )";
        
        if(mysqli_query($conn, $sqlInsert)){
            $userId = mysqli_insert_id($conn);  #Insert된 유저 고유 아이디
            
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