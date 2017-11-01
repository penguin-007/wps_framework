<?php

/**
 * Class WPS Cart 
 */

class WPS_Cart{

  function __construct() {
    // cart actions
    add_action( 'wp_ajax_nopriv_cart_actions', array( $this,'cart_actions' ) );
    add_action( 'wp_ajax_cart_actions', array( $this,'cart_actions' ) );
    // init_script
    add_action( 'wp_enqueue_scripts', array( $this,'init_script' ) );

    // init Order list
    require_once 'dashboard/orders.php';
    // init Setting
    require_once 'dashboard/settings.php';
  }

  // init_script
  public function init_script (){
    wp_enqueue_script ( 'wps_cart_script', trailingslashit( WPS_EXTENSIONS_URI ) . 'wps_cart/js/wps_cart_script.js', array('jquery'), WPS_VERSION, true );
  }

  // Cart Actions
  public function cart_actions(){
    $whatdo = htmlspecialchars($_POST["whatdo"]);
    if ( $whatdo ){
      unset(
        $_POST["action"],
        $_POST["whatdo"]
      );
      $this->$whatdo( $_POST );
    } else {
      echo "Cart actions not found";
    }
    exit();
  }


  // Add to cart
  public function addToCart( $data ){
    // start
    $result = array();
    // get post ID
    $post_id = $data["post_id"];
    // *COUNT
    // Если пользователь указывает количество - нужно им заменять текущее
    // Если у магазина нет поля "количество", значит каждое нажатие должно добавлять одну позицию
    $count = $data['count'] ? $data['count'] : 1;
    // *item_id идентефикатор генерируется на основе свойств товара
    ## TODO generateItemId( data )
    $item_id = $post_id;

    // get object from coockie
    $goods_obj = $this->getCartCookie();

    // если товар уже есть в корзине
    if ( $goods_obj->$item_id ){
      // количество
      if ( !$data['count'] && $count == 1 ){
        $goods_obj->$item_id->count++;
      } else {
        $goods_obj->$item_id->count = $count >= 1 ? $count : 1;
      }
    } else {
      $goods_obj->$item_id = (object) array(
        // set 
        "post_id" => $post_id,
        "item_id" => $item_id,
        "count"   => $count,
      );
    }

    // update data in coockie
    $this->setToCookie( $goods_obj );

    // считаем товары
    $countItem = 0;
    foreach( $goods_obj as $item ) {
      $result["count"] += $item->count;
    }

    // send result and exit
    print json_encode($result);
    exit();
  }


  // Remove From Cart
  public function removeFromCart( $data ){ 
    // start
    $result = array();
    // get item ID
    $item_id = $data["item_id"];
    // get object from coockie
    $goods_obj = $this->getCartCookie();
    if ( $item_id && $goods_obj->$item_id ){
      unset( $goods_obj->$item_id );
      // update data in coockie
      $this->setToCookie( $goods_obj );
      $result["success"] = $item_id." remove from cart";
    } else {
      $result["error"] = "WPS Cart error! Not found 'item_id'.";
    }
    // send result and exit
    print json_encode($result);
    exit();
  }


  // Clear Cart
  public function clearCart(){
    // start
    $result = array();
    // clear coockie
    $this->removeCartCookie();
    // send result and exit
    print json_encode($result);
    exit();
  }


  // Update Cart
  public function updateCart( $data ){
    // start
    $result = array();
    // items
    $items = $data["items"];
    // get object from coockie
    $goods_obj = $this->getCartCookie();
    if ( $items && $goods_obj ){
      foreach( $items as $item ) {
        $item_id = $item["item_id"];
        // get this
        $_this = $goods_obj->$item_id;
        // update
        $_this->count = $item["count"] >=1 ? $item["count"] : 1;
      }
      // update data in coockie
      $this->setToCookie( $goods_obj );
    }
    // send result and exit
    print json_encode($result);
    exit();
  }


  // Send Cart Order
  public function sendCartOrder( $data ){
    // start
    $result = array();

    // form for sending
    $form = array();

    /* get user data */
    parse_str( $data["form"], $form["message"] );

    /* get cart data */
    $form["products"] = WPS_Cart::getCartProduct();

    // set form template
    $form["form_template"] = "order";

    // render html
    $form_html = WPS_Mail::render_message( $form );

    // save data in admin
    $cur_count = get_option( 'wps__cart__order_counter' );
    $new_count = !$cur_count ? (int) 1 : ++$cur_count;
    update_option( 'wps__cart__order_counter', $new_count );

    $order_detail = array(
      'post_title'    => "Заказ №".$new_count,
      'post_type'     => 'wps_orders',
      'post_status'   => 'publish',
      'meta_input'    => array(
        'user_data'   => $form_html,
        'order_price' => WPS_Cart::get_total_price(),
        'currency'    => WPS_Cart::get_currency()
      )
    );
    wp_insert_post( $order_detail );

    // get options
    $options  = self::get_options();
    // if set reload
    $result["reload"] = $options["slug_page_thanks"] ? $options["slug_page_thanks"] : "";
    // send message on mail
    $to       = $options['general_email'];
    $sender   = 'wordpress@' . wps__get_sitename();
    $from     = get_option('blogname')." ";
    $subject  = "Заказ №".$new_count;

    $result["success"] = WPS_Mail::send_email(array(
      "to"          => $to,
      "from"        => $from,
      "sender"      => $sender,
      "subject"     => $subject,
      "message"     => $form_html,
    ));
    
    // remove items
    $this->removeCartCookie();

    // send result and exit
    print json_encode($result);
    exit();
  }


  // Coockie
  private function setToCookie( $data ){
    setcookie( "wps__cart_item", json_encode($data), time()+3600*5, '/', "");
  }

  public static function getCartCookie (){
    return json_decode(html_entity_decode(stripslashes($_COOKIE["wps__cart_item"]), ENT_QUOTES,'UTF-8'));
  }

  public function removeCartCookie(){
    setcookie("wps__cart_item", json_encode(), false, '/', "");
    unset( $_COOKIE[ 'wps__cart_item']); 
  }


  // CART METHODS
  public static function get_options(){
    // get options
    $options = get_option('wps_cart_module_settings');
    if ( is_array($options) ){
      return $options;
    }
  }

  public static function getCountCart( $goods_obj = NULL ){ 
    $countCall = 0;
    if( !$_COOKIE['wps__cart_item'] ) return $countCall;
    $goods_obj = $goods_obj ? $goods_obj : self::getCartCookie();
    if ( $goods_obj != "" ) {
      foreach($goods_obj as $value) {
        $countCall += $value->count;
      }
    }
    return $countCall;
    exit();
  }

  public static function getCartProduct(){
    $product = array();
    if( $_COOKIE['wps__cart_item'] ){
      $goods_obj = self::getCartCookie();
      foreach($goods_obj as $value) {
        $query = get_post($value->post_id);
        if ( $query != null ) {
          // data
          $product[] = array_merge((array)$query, (array)$value);
        }
      }
    }
    return $product;
  }

  public static function get_actualy_price( $id ){
    $item_static_price = get_post_meta( $id, "item_static_price", true);
    $item_action_price = get_post_meta( $id, "item_action_price", true);
    $item_status       = get_post_meta( $id, "item_status", true);
    if ( $item_static_price && $item_action_price && $item_status == 'on' ){
      $item_static_price = $item_action_price;
    } else {
      return $item_static_price;
    }
    return $item_static_price;
  }

  public static function get_total_price(){
    $goods_obj = self::getCartCookie();
    $total     = 0;
    if ( $goods_obj != "" ) {
      foreach ($goods_obj as $value) {
        $item_id = $value->post_id;
        $count   = $value->count;
        $sum     = self::get_actualy_price( $item_id );
        $total  += $sum * $count;
      }
    }
    return $total;
  }

  public static function get_currency(){
    $options  = self::get_options();
    $currency = $options["currency"];
    return $currency; 
  }

}
new WPS_Cart();