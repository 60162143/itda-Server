<?php
    require '../db/DBConfig.php';
    
    $storeId = isset($_GET["storeId"]) ? $_GET["storeId"] : 0;  # 가게 고유 아이디
    
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        # 가게 메뉴 조회
        $sql = "SELECT M.MENU_ID     # 메뉴 고유 아이디
                             , M.ST_ID           # 가게 고유 아이디
                             , M.MENU_NM     # 메뉴 명
                             , M.MENU_PRI    # 메뉴 금액
                             , M.MENU_ORD   # 메뉴 정렬 순서
                    FROM MENU M
                    WHERE M.ST_ID = $storeId";
        
        $result = mysqli_query($conn, $sql);
    
        $menusArray = array();
    
        for($i = 0; $row = mysqli_fetch_Array($result); $i++){
    
            $menusArray[$i] = array(
                "menuId" => $row['MENU_ID']
                , "storeId" => $row['ST_ID']
                , "menuName" => $row['MENU_NM']
                , "menuPrice" => $row['MENU_PRI']
                , "menuOrder" => $row['MENU_ORD']
            );
            
        }
    
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode(array("menu"=>$menusArray), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
        echo $json;
    }else{
        echo "error";
    }
?>