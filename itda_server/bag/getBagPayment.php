<?php
    require '../db/DBConfig.php';
    
    $userId = isset($_GET["userId"]) ? $_GET["userId"] : 0;     # 유저 고유 아이디
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        # 유저가 결제한 결제 정보 조회
        $sqlPayment = "SELECT P.PAY_ID  # 결제 고유 아이디
                            , P.ST_ID      # 가게 고유 아이디
                            , P.USER_ID # 유저 고유 아이디
                            , P.PAY_PRI  # 결제 금액
                            , IF(P.USED_YN = 'T', '사용 완료', IF(DATE(NOW()) <= P.EXP_DATE, '사용 가능', '기간 만료')) USED_STATUS # 결제 티켓 사용 상태
                            , P.USED_CPN_ID     # 사용한 쿠폰 고유 아이디
                            , IFNULL((SELECT C.DIS_RATE FROM COUPON C WHERE C.CPN_ID = P.USED_CPN_ID), 0) DIS_RATE  # 사용한 쿠폰 할인 율
                            , DATE_FORMAT(P.REG_DATE,'%Y.%m.%d') AS PAY_DATE    # 결제 일자
                            , DATE_FORMAT(P.EXP_DATE,'%Y.%m.%d') AS EXP_DATE    # 티켓 만료 일자
                            , S.ST_NM   # 가게 명
                            , IFNULL(CONCAT(F.FILE_PATH, F.FILE_NM, '.', F.FILE_ETS), '/ftpFileStorage/noUser.png') ST_TMN_IMG  # 가게 썸네일 이미지
                    FROM PAYMENT P
                    LEFT OUTER JOIN STORE S
                        ON P.ST_ID = S.ST_ID
                    LEFT OUTER JOIN FILE F 
                        ON S.ST_TMN_IMG_ID = F.FILE_ID
                    WHERE P.USER_ID = $userId
                    ORDER BY P.REG_DATE DESC";
        
        $resultPayment = mysqli_query($conn, $sqlPayment);
    
        $paymentsArray = array();
    
        for($i = 0; $row = mysqli_fetch_Array($resultPayment); $i++){
            
            $paymentId = $row['PAY_ID'];    # 결제 고유 아이디
            
            # 결제한 메뉴 목록 조회
            $sqlPaymentMenu = "SELECT P_M.PAY_MENU_ID   # 결제한 메뉴 테이블 고유 아이디
                                                    , P_M.PAY_ID         # 결제 고유 아이디
                                                    , P_M.MENU_ID     # 메뉴 고유 아이디
                                                    , P_M.MENU_CNT  # 결제 메뉴 수량
                                                    , M.MENU_NM       # 메뉴 명
                                                    , M.MENU_PRI      # 메뉴 금액
                                            FROM PAYMENT_MENU P_M
                                            LEFT OUTER JOIN MENU M
                                                ON P_M.MENU_ID = M.MENU_ID
                                            WHERE P_M.PAY_ID = $paymentId";
            
            $resultPaymentMenu = mysqli_query($conn, $sqlPaymentMenu);
            
            $paymentMenusArray = array();
            
            for($j = 0; $rowMenu = mysqli_fetch_Array($resultPaymentMenu); $j++){
                
                $paymentMenusArray[$j] = array(
                    "paymentMenuId" => $rowMenu['PAY_MENU_ID']
                    , "paymentId" => $rowMenu['PAY_ID']
                    , "menuId" => $rowMenu['MENU_ID']
                    , "menuCount" => $rowMenu['MENU_CNT']
                    , "menuName" => $rowMenu['MENU_NM']
                    , "menuPrice" => $rowMenu['MENU_PRI']
                );
    
            }
            
            $paymentsArray[$i] = array(
                "paymentId" => $row['PAY_ID']
                , "storeId" => $row['ST_ID']
                , "userId" => $row['USER_ID']
                , "paymentPrice" => $row['PAY_PRI']
                , "paymentUsedStatus" => $row['USED_STATUS']
                , "paymentUsedCouponId" => $row['USED_CPN_ID']
                , "paymentUsedCouponDisRate" => $row['DIS_RATE']
                , "paymentPayDate" => $row['PAY_DATE']
                , "paymentExpDate" => $row['EXP_DATE']
                , "storeName" => $row['ST_NM']
                , "storeImage" => $row['ST_TMN_IMG']
                , "paymentMenu" => $paymentMenusArray
            );
        }
    
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("payment"=>$paymentsArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
?>