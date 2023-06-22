<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : 0;       # 유저 고유 아이디
    $storeId = isset($_POST["storeId"]) ? $_POST["storeId"] : 0;    # 가게 고유 아이디
    $couponId = isset($_POST["couponId"]) ? $_POST["couponId"] : 0;     # 결제 시 사용한 쿠폰 고유 아이디
    $paymentPrice = isset($_POST["paymentPrice"]) ? $_POST["paymentPrice"] : 0;     # 결제 금액
    $menuArr = isset($_POST["paymentMenus"]) ? $_POST["paymentMenus"] : [];      # 결제한 메뉴
    
    $arr = array();
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        # 결제 데이터 Insert
        $sqlPaymentInsert = "INSERT INTO PAYMENT
                                            ( USER_ID          # 유저 고유 아이디
                                            , ST_ID               # 가게 고유 아이디
                                            , PAY_PRI            # 결제 금액
                                            , USED_YN          # 티켓 사용 여부
                                            , USED_CPN_ID   # 사용한 쿠폰 고유 아이디
                                            , REG_USER_ID   # 티켓 결제일
                                            , EXP_DATE )      # 티켓 만료일
                                        VALUES
                                            ( $userId
                                            , $storeId
                                            , $paymentPrice
                                            , 'F'
                                            , $couponId
                                            , $userId
                                            , DATE_ADD(NOW(), INTERVAL 7 DAY) )";
        
        if(mysqli_query($conn, $sqlPaymentInsert)){
            $paymentId = mysqli_insert_id($conn);   # Insert한 결제 데이터 고유 아이디
            
            foreach (json_decode($menuArr, true) as $key => $value){
                $menuId = $value['menuId'];                 # 메뉴 고유 아이디
                $menuCount = $value['menuCount'];      # 결제 메뉴 수량
                
                # 결제한 메뉴 Insert
                $sqlPaymentMenuInsert = "INSERT INTO PAYMENT_MENU
                        ( PAY_ID              # 결제 고유 아이디
                        , MENU_ID           # 메뉴 고유 아이디
                        , MENU_CNT        # 결제 메뉴 수량
                        , REG_USER_ID ) # 최초 작성자
                    VALUES
                        ( $paymentId
                        , $menuId
                        , $menuCount
                        , $userId)";
                
                mysqli_query($conn,$sqlPaymentMenuInsert);
            }
            
            # 사용 쿠폰이 있을 경우 사용 여부 변경
            $sqlCouponUpdate = "UPDATE COUPON
                                             SET USED_YN = 'T'  # 쿠폰 사용 여부
                                            WHERE CPN_ID = $couponId";
            
            mysqli_query($conn,$sqlCouponUpdate);
            
            # 결제한 데이터 조회
            $sqlPaymentSelect = "SELECT P.PAY_ID    # 결제 고유 아이디
                                                    , P.ST_ID        # 가게 고유 아이디
                                                    , P.USER_ID    # 유저 고유 아이디
                                                    , P.PAY_PRI     # 결제 금액
                                                    , DATE_FORMAT(P.REG_DATE,'%Y.%m.%d') AS PAY_DATE    # 티켓 결제일
                                                    , DATE_FORMAT(P.EXP_DATE,'%Y.%m.%d') AS EXP_DATE    # 티켓 만료일
                                                    , S.ST_NM       # 유저 명
                                                    , IFNULL(CONCAT(F.FILE_PATH, F.FILE_NM, '.', F.FILE_ETS), '/ftpFileStorage/noUser.png') ST_TMN_IMG  # 유저 프로필 이미지 경로
                                            FROM PAYMENT P
                                            LEFT OUTER JOIN STORE S
                                                ON P.ST_ID = S.ST_ID
                                            LEFT OUTER JOIN FILE F 
                                                ON S.ST_TMN_IMG_ID = F.FILE_ID
                                            WHERE P.PAY_ID = $paymentId";
            
            $resultPayment = mysqli_query($conn, $sqlPaymentSelect);
            
            $paymentArray = array();
            
            for($i = 0; $row = mysqli_fetch_Array($resultPayment); $i++){
                
                $paymentArray[$i] = array(
                    "paymentId" => $row['PAY_ID']
                    , "storeId" => $row['ST_ID']
                    , "userId" => $row['USER_ID']
                    , "paymentPrice" => $row['PAY_PRI']
                    , "paymentDate" => $row['PAY_DATE']
                    , "expireDate" => $row['EXP_DATE']
                    , "storeName" => $row['ST_NM']
                    , "storeImage" => $row['ST_TMN_IMG']
                );
                
            }
            
            $arr["payment"] = $paymentArray;
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