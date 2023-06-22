# itda-Server

## **📝 개요**

> **프로젝트:** 세상을 연결해주는 도구 **잇다(Itda**)
>
> **기획 및 제작:** 오태근
>
> **분류:** Php Mysql 연동 및 통신
>
> **제작 기간:** 23.02 ~ 23.06
>
> **사용 라이브러리:**
  - **MySQLi Library** : MySQLi는 MySQL 데이터베이스와 상호작용하기 위한 PHP의 확장 라이브러리. MySQLi는 객체 지향적인 접근 방식을 사용하며, 이전 버전의 MySQL과의 호환성도 지원
  ```python
    <?php
      // DB 연동을 시작합니다.
      $con=mysqli_connect("호스트명", "DB ID", "DB PW", "DB 이름");
  
      // mysqli_connect()에 대한 마지막 호출에 대한 오류 코드 값을 반환한다.
      // echo mysqli_connect() 에서 0은 오류가 발생하지 않았음을 의미한다
      if (mysqli_connect_errno($con)) {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
      }
  
  
      /* select data */
      // 1. 실행할 쿼리문을 작성합니다.
      $selectSQL = "select * from ttest";
      $result = mysqli_query($con, $selectSQL);
  
      // 출력할 데이터를 저장할 배열변수 선언
      // 데이터는 json-array 형식으로 출력할 것임 (일반적으로 이렇게 함)
      $data = array();
      
      if ($result) {
          while ($row=mysqli_fetch_array($result)) {
              array_push($data,array('컬럼명'=>$row[0],'컬럼명'=>$row[1]));
          }
          header('Content-Type: application/json; charset=utf8');
          $json = json_encode(array("테이블이름"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
  
  		echo $json; // => 출력되는 값이 이 코드로 하여금 android로 전송된다..
  
  	} else {
          echo "SQL문 처리중 에러 발생 : ";
          echo mysqli_error($con);
      }
  
      // DB 연동을 종료 합니다
      mysqli_close($con);
  ?>
```

> **API 명세서:**

Index|Method|URI|Description
---|---|---|---
1|GET|/bag/getBagCoupon.php?userId={userId}|유저가 보유한 쿠폰 목록 조회
2|GET|/bag/getBagPayment.php?userId={userId}|유저가 결제한 결제 목록 조회
3|GET|/collabo/getCollabo.php|협업 목록 조회
4|GET|/info/getInfoCollabo.php?storeId={storeId}|협업 가게 상세 정보 조회
5|GET|/info/getInfoMenu.php?storeId={storeId}|가게 메뉴 목록 조회
6|GET|/info/getInfoPaymentCoupon.php?storeId={storeId}&&userId={userId}|유저가 보유한 쿠폰 상세 정보 조회
7|GET|/info/getInfoPhoto?storeId={storeId}&&userId={userId}|가게에 업로드 된 사진 목록 조회
8|GET|/info/getInfoReview.php?storeId={storeId}&&userId={userId}&&loginUserId={loginUserId}|가게에 작성된 리뷰 목록 조회
9|GET|/info/getInfoReviewComment.php?reviewId={reviewId}|작성된 리뷰의 댓글 조회
10|POST|/info/insertInfoPayment.php|유저가 결제한 데이터 저장
11|POST|/info/insertInfoPaymentCoupon.php|결제 후 협업 할인 조건에 만족하는 쿠폰 데이터 저장
12|POST|/info/insertInfoReview.php|유저가 작성한 리뷰 데이터 저장
13|POST|/info/insertInfoReviewComment.php|리뷰에 유저가 작성한 댓글 데이터 저장
14|POST|/info/insertInfoReviewPhoto.php|리뷰 작성 시 업로드한 사진 파일 저장
15|POST|/info/updateInfoReviewHeart.php|유저가 작성한 리뷰의 좋아요 수 갱신
16|POST|/login/getLoginUser.php|로그인 정보 조회
17|POST|/login/getLoginUserId.php|이메일이 회원가입된 이메일인지 조회
18|POST|/login/insertKakaoLoginUser.php|카카오 회원가입 데이터 저장
19|POST|/login/insertLoginUser.php|일반 회원가입 데이터 저장
20|POST|/login/updateLoginUserOption.php|회원가입된 유저 정보 갱신
21|GET|/map/getMapStore.php?schText={schText}&&latitude={latitude}&&longitude={longitude}|지도 화면 내 가게 데이터 조회
22|POST|/mypage/deleteBookmarkCollabo.php|유저가 찜한 협업 목록 삭제
23|POST|/mypage/deleteBookmarkStore.php|유저가 찜한 가게 목록 삭제
24|POST|/mypage/deletePhoto.php|유저가 리뷰 작성시 업로드한 사진 삭제
25|POST|/mypage/deleteReview.php|유저가 작성한 리뷰, 리뷰에 작성된 댓글, 업로드 된 사진 삭제
26|POST|/mypage/deleteUserProfile.php|유저의 프로필 이미지 삭제
27|GET|/mypage/getBookmarkCollabo.php?userId={userId}|유저가 찜한 협업 목록 조회
28|GET|/mypage/getBookmarkStore.php?userId={userId}&&latitude={latitude}&&longitude={longitude}|유저가 찜한 가게 목록 조회
29|POST|/mypage/insertBookmarkCollabo.php|유저가 찜한 협업 데이터 저장
30|POST|/mypage/insertBookmarkStore.php|유저가 찜한 가게 데이터 저장
31|POST|/mypage/updateUserBirthday.php|유저의 생일 정보 수정
32|POST|/mypage/updateUserName.php|유저의 이름 정보 수정
33|POST|/mypage/updateUserNumber.php|유저의 연락처 정보 수정
34|POST|/mypage/updateUserPassword.php|유저의 비밀번호 정보 수정
35|POST|/mypage/updateUserProfile.php|유저의 프로필 이미지 정보 수정
36|GET|/store/getCategory.php|카테고리 정보 조회
37|GET|/store/getMainBookmarkCollabo.php?userId={userId}|유저가 찜한 협업 목록 간단 데이터 조회
38|GET|/store/getMainBookmarkStore.php?userId={userId}|유저가 찜한 가게 데이터 조회
39|GET|/store/getMainStore.php?storeId={storeId}&&categoryId={categoryId}&&schText={schText}|메인 가게 데이터 조회

> **문의:** no2955922@naver.com
