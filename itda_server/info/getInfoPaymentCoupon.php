<?php
    require '../db/DBConfig.php';
    
    $storeId = isset($_GET["storeId"]) ? $_GET["storeId"] : 0;  # 가게 고유 아이디
    $userId = isset($_GET["userId"]) ? $_GET["userId"] : 0;     # 유저 고유 아이디
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        # 유저가 보유한 쿠폰 조회
        $sql = "SELECT C.CPN_ID         # 쿠폰 고유 아이디
                             , C.ST_ID           # 가게 고유 아이디
                             , C.USER_ID       # 유저 고유 아이디
                             , C.DIS_RATE     # 쿠폰 할인 율
                            , DATE_FORMAT(C.EXP_DATE,'%Y.%m.%d') AS EXP_DATE    # 쿠폰 만료일
                    FROM COUPON C
                    WHERE C.ST_ID = $storeId
                        AND C.USER_ID = $userId
                        AND USED_YN = 'F'
                        AND DATE(NOW()) <= C.EXP_DATE
                    ORDER BY C.EXP_DATE";
        
        $result = mysqli_query($conn, $sql);
    
        $couponsArray = array();
    
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
    
            $couponsArray[$i] = array(
                "couponId" => $row['CPN_ID']
                , "storeId" => $row['ST_ID']
                , "userId" => $row['USER_ID']
                , "discountRate" => $row['DIS_RATE']
                , "expDate" => $row['EXP_DATE']
            );
            
        }
    
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("coupon"=>$couponsArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
?>