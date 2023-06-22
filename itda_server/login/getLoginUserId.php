<?php
    require '../db/DBConfig.php';
    
    $userEmail = isset($_POST["userEmail"]) ? $_POST["userEmail"] : "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 회원가입된 이메일인지 확인
        $sql = "SELECT IFNULL((SELECT USER_ID   
                                            FROM USER 
                                            WHERE USER_EML = '$userEmail'), 0) USER_ID  # 유저 고유 아이디
                    FROM DUAL";
        
        $result = mysqli_query($conn, $sql);
        
        $userIdArray = array();
        
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
            
            $userIdArray[$i] = array("userId" => $row['USER_ID']);
            
        }
        
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("userId"=>$userIdArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
?>