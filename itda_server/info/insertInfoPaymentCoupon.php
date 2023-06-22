<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;       # 유저 고유 아이디
    $storeId = isset($_POST["storeId"]) ? $_POST["storeId"] : 0;    # 가게 고유 아이디
    $paymentPrice = isset($_POST["paymentPrice"]) ? $_POST["paymentPrice"] : 0;     # 결제 금액
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $arr["success"] = "1";
        
        # 협업 기간이 끝나지 않은 협업 데이터 조회
        $sqlSelect = "SELECT C.POST_ST_ID   # 뒷 가게 고유 아이디
                                    , C.PRV_DIS_CON     #   앞 가게 할인 조건
                                    , C.POST_DIS_RATE   # 뒷 가게 할인 율
                            FROM COLLABORATION C
                            WHERE C.PRV_ST_ID = $storeId
                                AND DATE(NOW()) >= C.START_DATE
                                AND DATE(NOW()) <= C.END_DATE";
        
        $result = mysqli_query($conn, $sqlSelect);
        
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
            
            $postStoreId = $row['POST_ST_ID'];  # 뒷 가게 고유 아이디
            $prvStoreDiscountCondition = $row['PRV_DIS_CON'];   # 앞 가게 할인 조건
            $postStoreDisRate = $row['POST_DIS_RATE'];  # 뒷 가게 할인 율
            
            if($prvStoreDiscountCondition <= $paymentPrice){
                # 협업 할인 조건에 만족하는 쿠폰 데이터 Insert
                $sqlInsert = "INSERT INTO COUPON
                                        ( USER_ID       # 유저 고유 아이디
                                        , ST_ID            # 가게 고유 아이디
                                        , DIS_RATE      # 쿠폰 할인 율
                                        , USED_YN       # 쿠폰 사용 여부
                                        , EXP_DATE )   # 쿠폰 만료일
                                    VALUES
                                        ( $userId
                                        , $postStoreId
                                        , $postStoreDisRate
                                        , 'F'
                                        , DATE_ADD(NOW(), INTERVAL 7 DAY) )";
                
                if(mysqli_query($conn, $sqlInsert)){
                    $arr["success"] = "1";
                }else{
                    $arr["success"] = "-1";
                }
            }
        }
        
    }else{
        $arr["success"] = "error";
    }
    
    header('Content-Type: application/json; charset=utf8');
    $json = json_encode($arr, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    echo $json;
?>