<?php

/**
 * Class cart 
 */

class WPS_Cart {

  public $domain_name;
  
  function __construct( ) {

    $this->domain_name = wps__get_sitename();

    // addToCart
    add_action( 'wp_ajax_nopriv_cart_action', array( $this,'addToCart' ) );
    add_action( 'wp_ajax_cart_action', array( $this,'addToCart' ) );

    // clear_cart
    add_action( 'wp_ajax_nopriv_clear_cart', array( $this,'clear_cart' ) );
    add_action( 'wp_ajax_clear_cart', array( $this,'clear_cart' ) );

    // cart_go_order
    add_action( 'wp_ajax_nopriv_cart_go_order', array( $this, 'cart_go_order' ) );
    add_action( 'wp_ajax_cart_go_order', array( $this, 'cart_go_order' ) );

    // select_sity
    add_action( 'wp_ajax_nopriv_select_sity', array( $this, 'select_sity' ) );
    add_action( 'wp_ajax_select_sity', array( $this, 'select_sity' ) );

    // Mail
    add_action( 'wp_ajax_nopriv_cart_order_form', array( $this,'order_mail' ) );
    add_action( 'wp_ajax_cart_order_form', array( $this,'order_mail' ) );

    // init_script
    add_action( 'wp_enqueue_scripts', array( $this,'init_script' ) );
    
    // init Order list
    require_once 'orders.php';
  }
  
  // init_script
  public function init_script (){
    wp_enqueue_script  ( 'wps_cart_script', trailingslashit( WPS_MODULES_URI ) . 'wps_cart/wps_cart_script.js', array('jquery'), WPS_VERSION, true );
    /*
    wp_localize_script ( 'cart_script', 'theme_ajax',
      array(
        'url' => admin_url('admin-ajax.php')
      )
    );
    */
  }


  /* addToCart */
  public function addToCart(){
    // item data
    $uniq_Id    = uniq_hash(1);
    $post_id    = htmlspecialchars($_POST['id']);
    $size_item  = $_POST['size']  ? htmlspecialchars( $_POST['size'] ) : "";
    $count_item = htmlspecialchars($_POST['count']) ? htmlspecialchars($_POST['count']) : 1;

    // получить текущий объект с данными о товарах
    $goods_obj  = getCartCookie();

    // товар уже в корзине
    $already_in_cart = false; 

    // просмотреть все товары в объекте
    foreach($goods_obj as $current_item){
      // 1) проверка: есть ли товар с таким ID
      if ( $post_id == $current_item->id ){
        // 2) проверяем: сходятся ли данные товара
        if ( $current_item->size == $size_item ){
          // товар уже есть в корзине
          $current_item->count = $count_item;
          $already_in_cart = true;
        }
      }
    }
    
    // если товара еще нет в корзине
    if ( !$already_in_cart ){
      $goods_obj->$uniq_Id = (object) array( 
        "uniqId"    => $uniq_Id,
        "id"        => $post_id,
        "size"      => $size_item,
        "count"     => $count_item
      );
    }

    $this->cartUpdateCookie( $goods_obj );

    // считаем товары
    $countItem = 0;
    foreach( $goods_obj as $value ) {
      $countItem += $value->count;
    }
    echo $countItem;
    die();
  }


  public static function wps_get_cart_product(){
    if( $_COOKIE['wps_cart_item'] ){
      $goods_obj = getCartCookie();

      foreach($goods_obj as $value) {
        $query = get_post($value->id);
        if ( $query == null ) {
          continue;
        }
        // data
        $query->uniqId  = $value->uniqId;
        $query->size    = $value->size;
        $query->count   = $value->count;
        $product[] = $query;
      }
    }
    return $product;
  }


  /* wps_clear_cart */
  public function clear_cart(){
    $this->cartRemoveCookie();
  }


  /* cart_go_order */
  public function cart_go_order(){
    $goods      = $_POST['goods'];
    $goods_obj  = getCartCookie();

    foreach($goods as $val){
      $good_uniId = $val[0];
      $good_size  = $val[1];
      $post_count = $val[2];

      $current_item = $goods_obj->$good_uniId;
      $current_item->size  = $good_size;
      $current_item->count = $post_count;
    }

    $this->cartUpdateCookie($goods_obj);
    die();
  }


  public function select_sity(){
    $city      = htmlspecialchars($_POST['city']);
    $data_city = htmlspecialchars($_POST['data_city']);

    $warehouses = simplexml_load_file( PARENT_DIR.'/extends/delivery_data/warehouses.xml' );
    $data = $warehouses->data->item;
    unset( $warehouses );

    $html = "";

    foreach ($data as $value) {
      $city    = $value->DescriptionRu;
      if ( $data_city == $value->CityRef ){
        $html .= "<option value='{$city}'>{$city}</option>";
      }
    }
    unset( $data );

    exit( $html );
  }



  public function order_mail(){

    /* user data */
    parse_str( $_POST['msg'], $message_arr);
    
    /* end user data */
    $order_id = uniq_hash(4);

    /* get items in cart */
    $goods_obj = getCartCookie();


    #### ORDER 

    $order_items .= '<table cellspacing="0" align="center" border="1" bgcolor="#F8F8F8" cellpadding="0" style="width:100%; max-width:600px;" >';
    $order_items .= '<tr><td colspan="5" style="padding: 5px 10px; text-align:center;">Состав заказа:</td></tr>';
    $order_items .= '
      <tr>
        <td style="padding: 10px;">Товар</td>
        <td style="padding: 10px;">Размер</td>
        <td style="padding: 10px;">Цена</td>
        <td style="padding: 10px;">Количество</td>
        <td style="padding: 10px;">Сумма</td>
      </tr>';

    $total_in_cart = 0;

    foreach($goods_obj as $value) {
      $title     = get_the_title( $value->id );
      $size      = $value->size;
      $count     = $value->count;

      $item_static_price = $this->wps_cart__get_actualy_price( $value->id );
      $sum = $item_static_price * $count;
      $total_in_cart += $sum;

      $order_items .= '
      <tr>
        <td style="padding: 10px;">«'.$title.'»</td>
        <td style="padding: 10px;">'.$size.'</td>
        <td style="padding: 10px;">'.$item_static_price.' грн</td>
        <td style="padding: 10px;">'.$count.'</td>
        <td style="padding: 10px;">'.$sum.' грн</td>
      </tr>';
    }
    $order_items .= '
    <tr>
      <td style="padding: 10px;" colspan="4" >Всего к оплате</td>
      <td style="padding: 10px;">'.$total_in_cart.' грн</td>
    </tr>
    </table>';

    #### USER 
    $user_info  = '<html><body>';
    $user_info .= '<table cellspacing="0" align="center" border="1" bgcolor="#F8F8F8" cellpadding="0" style="width:100%; max-width:600px;" >';
    $user_info .= '<tr><td colspan="2" style="padding: 5px 10px; text-align:center;">Данные отправителя:</td></tr>';
    foreach ($message_arr as $key => $value) {
     $user_info .= '
      <tr>
        <td width="30%" style="padding: 5px 10px;">'.$key.':</td>
        <td style="padding: 4px 8px;">'.htmlspecialchars( $value ).'</td>
      </tr>';
    }
    $user_info .= '</table>'; 
    $user_info .= '</body></html>';

    /* project number */
    $cur_count = get_option( 'wps_cart__order_counter' );
    $new_count = !$cur_count ? (int) 1 : ++$cur_count;
    update_option( 'wps_cart__order_counter', $new_count );

    $order_detail = array(
      'post_title'   => "Заказ №".$new_count,
      'post_type'    => 'wps_orders',
      'post_status'  => 'publish',
      'meta_input'   => array(
        'user_data'  => $user_info, 
        'order_data' => $order_items, 
      )
    );

    wp_insert_post( $order_detail );
    /* end order template in admin */


    #### MAIL
    /* base */
    $to_option    = get_option('wps_theme_main_settings');
    $to           = $to_option['theme_email'];
    $sender       = 'wordpress@' . wps__get_sitename();
    $project_name = get_option('blogname')." ";
    $subject      = "Заказ";

    $message .= $user_info;
    $message .= '<br>';
    $message .= $order_items;

    /* header */
    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
    $headers .= 'From: ' . $project_name . '<'. $sender . '>';

    // send mail
    wp_mail( $to, $subject, $message, $headers );

    // remove items
    $this->cartRemoveCookie();

    exit( json_encode("Успешно") );
  }


  /* Cookie Methods */
  public function remove_cookie(){
    //setcookie("cart_item", json_encode(), time()+3600*5, '/', $this->domain_name );
    setcookie("wps_cart_item", json_encode(), false, '/', "");
    unset( $_COOKIE[ 'wps_cart_item']);
  }

  public function update_cookie($data){
    //setcookie("cart_item", json_encode($data), time()+3600*5, '/', $this->domain_name );
    setcookie( "wps_cart_item", json_encode($data), time()+3600*5, '/', "");
  }

  public function get_cookie(){
    return json_decode(html_entity_decode(stripslashes($_COOKIE["wps_cart_item"]), ENT_QUOTES,'UTF-8'));
  }


  /* wps cart methods */ 
  public static function wps_cart__get_actualy_price( $id ){
    $item_static_price = get_post_meta( $id, "item_static_price", true);
    $item_action_price = get_post_meta( $id, "item_action_price", true);
    $item_status       = get_post_meta( $id, "item_status", true);
    if ( $item_static_price && $item_action_price && $item_status == 'sale' ){
      $item_static_price = $item_action_price;
    } else {
      return $item_static_price;
    }
    return $item_static_price;
  }

  public static function wps_cart__get_total_in_cart_price(){
    $goods_obj = getCartCookie();
    $total_sum = 0;
    if ( $goods_obj != "" ) {
      foreach ($goods_obj as $value) {
        $item_id = $value->id;
        $count   = $value->count;
        $sum     = self::wps_cart__get_actualy_price( $item_id );
        $total_sum += $sum * $count;
      }
    }
    return $total_sum;
  }

  public static function cartCallCount( $goods_obj = NULL ){ // ????
    if( !$_COOKIE['wps_cart_item'] ) return false;
    $countCall = 0;
    $goods_obj = $goods_obj ? $goods_obj : getCartCookie();
    if ( $goods_obj != "" ) {
      foreach($goods_obj as $value) {
        $countCall += $value->count;
      }
    }
    return $countCall;
  }


  /* other methods */
  public function uniq_hash($n=4){
    $chars   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randabc = '';
    for($ichars = 0; $ichars < $n; ++$ichars) {
      $random   = str_shuffle($chars);
      $randabc .= $random[0];
    }
    $time=time();
    return substr_replace($time,$randabc,3,0); 
  }
} 

//new WPS_Cart();