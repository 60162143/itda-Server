<?php
    require '../db/DBConfig.php';
    
    $email = isset($_POST["email"]) ? $_POST["email"] : "";     # 로그인 이메일
    $password = isset($_POST["password"]) ? $_POST["password"] : "";    # 로그인 비밀번호
    $loginFlag = isset($_POST["loginFlag"]) ? $_POST["loginFlag"] : "normal";   # 로그인 방식 Flag ( 일반 로그인 : noraml, 카카오 로인 : kakao )

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $sql = "SELECT U.USER_ID          # 유저 고유 아이디
                            , U.USER_EML         # 유저 이메일
                            , U.USER_PWD        # 유저 비밀번호
                            , IFNULL(CONCAT(F.FILE_PATH, F.FILE_NM, '.', F.FILE_ETS), '/ftpFileStorage/noUser.png') USER_PRF_IMG    # 유저 프로필 이미지 경로
                            , U.USER_NUM        # 유저 연락처
                            , U.USER_NM          # 유저 명
                            , DATE_FORMAT(U.USER_BIR_DATE, '%Y.%m.%d') USER_BIR_DATE    # 유저 생일
                            , U.USER_LOGIN_FLAG     # 유저 로그인 방식 Flag ( 일반 로그인 : noraml, 카카오 로인 : kakao )
                    FROM USER U
                    LEFT OUTER JOIN FILE F 
                        ON U.USER_PRF_IMG_ID = F.FILE_ID
                    WHERE BINARY(USER_EML) = '$email'";
        
        # 일반 로그인일 경우 비밀번호 확인
        if($loginFlag == "normal"){
            $sql.=  "AND USER_PWD = '$password'";
        }
        
        $result = mysqli_query($conn, $sql);

        $userArray = array();
        
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
            
            $userArray[$i] = array(
                "userId" => $row['USER_ID']
                , "userEmail" => $row['USER_EML']
                , "userPassword" => $row['USER_PWD']
                , "userProfileImage" => $row['USER_PRF_IMG']
                , "userNumber" => $row['USER_NUM']
                , "userName" => $row['USER_NM']
                , "userBirthday" => $row['USER_BIR_DATE']
                , "userLoginFlag" => $row['USER_LOGIN_FLAG']
            );
            
        }
        
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("user"=>$userArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
?>