<?php 
function om_write(){
    $html = '
    <form method="post" action="'.esc_url( admin_url( 'admin-post.php' ) ).'">
        <input type="text" name="wr1">
        <input type="text" name="wr2">
        <input type="hidden" name="action" value="omnius_post">'.
        wp_nonce_field( 'omnius_post', 'omnius_post_field' )
        .'<input type="submit" value="submit">
    </form>
    ';
    return $html;
}
function om_sc_write(){
    add_shortcode('om_write','om_write');
}
add_action('init','om_sc_write');
/*
여기까지 숏코드 등록을 위한코드
*/







/*
action 주소는 꼭 여기로해야됨
action="'.esc_url( admin_url( 'admin-post.php' ) ).'"

name은 꼭 action으로
value는 원하는걸로 하면됨
<input type="hidden" name="action" value="omnius_post">

인젝션공격방지를 위해서 넣어줌(value에 넣은 값을 기준으로 만들면 됨)
wp_nonce_field( 'omnius_post', 'omnius_post_field' )

admin-post를 위한 것은 보통 두가지 모두 넣어줌
add_action( 'admin_post_nopriv_omnius_post', 'func_omnius_post' );
add_action( 'admin_post_omnius_post', 'func_omnius_post' );
로그인 하지않은 사람과 로그인한사람에게 보여줄 함수임
add_action 함수 두번째 인자로 함수이름 넣음

최종적으로 admin-post.php페이지에 와서 받은 post값으로 어떤걸 할지 func_omnius_post이름의 함수로 만들면 됨
*/


add_action( 'admin_post_nopriv_omnius_post', 'func_omnius_post' );
add_action( 'admin_post_omnius_post', 'func_omnius_post' );

function func_omnius_post(){
    global $wpdb;
    $wpdb->query(
        $wpdb->prepare(
            "insert into wp_test (wr1, wr2) values (%s,%d)",
            $_POST['wr1'],(int)$_POST['wr2']
        )
    );
    echo "<script>alert('ok');location.href='http://localhost/wordpress';</script>";
}







/*
여기부터 관리자 메뉴 커스텀

*/


 // omnius admin_menu add
 add_action('admin_menu', 'register_custom_menu_page');

  function register_custom_menu_page() {
    add_menu_page('custom','custom','manage_options','custom_menu','custom_menu_page','',6);
  }

  function custom_menu_page() {
    echo '<hr>';
    echo 'It works!<br>';
    global $wpdb;
    $tyty = $wpdb->get_results(
      $wpdb->prepare(
        "select * from wp_test"
      ),OBJECT
    );
    foreach ($tyty as $key) {
      $rst .= "content is {$key->wr1sss}<br>";
    }
    echo $rst;
  }


/*
OBJECT – 결과는 객체로 출력됩니다.
ARRAY_A – 결과는 연관 배열로 출력됩니다.
ARRAY_N – 결과는 숫자 인덱스 배열로 출력됩니다.
*/


  // omnius admin_menu add
  /*

  -->
  <?php
add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
?>

아이콘을 지정하지 않으면, 기본적으로 기어(gear) 아이콘이 표시됩니다.

3. 마지막으로, 위에서 언급한 함수에서 호출하는 함수 custom_menu_page 에서 실제 화면을 출력(echo)하게 됩니다.

가장 먼저 hr 과 같은 태그를 넣은 것은, 어드민 바에 의해 가려지는 부분을 차지하기 위한 작은 팁입니다. ^^

메뉴가 표시될 위치를 정하고 싶다면, 아래의 표를 참고하셔서 적절한 위치에 넣으시면 됩니다.
예제에서는 6번을 명시하여(함수 가장 마지막 파라미터), 글(Posts) 다음에 메뉴를 배치한 경우입니다.

 2 Dashboard
 4 Separator
 5 Posts
 10 Media
 15 Links
 20 Pages
 25 Comments
 59 Separator
 60 Appearance
 65 Plugins
 70 Users
 75 Tools
 80 Settings
 99 Separator

*/


?>