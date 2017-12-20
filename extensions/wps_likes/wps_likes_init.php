<?php

/**
 * Class WPS Likes 
 */

class WPS_Likes{

  function __construct() {
    // likes actions
    add_action( 'wp_ajax_nopriv_likes_actions', array( $this,'likes_actions' ) );
    add_action( 'wp_ajax_likes_actions', array( $this,'likes_actions' ) );
    // init_script
    add_action( 'wp_enqueue_scripts', array( $this, 'init_script' ) );
  }


  // init_script
  public function init_script (){
    wp_enqueue_script ( 'wps_likes_script', trailingslashit( WPS_EXTENSIONS_URI ) . 'wps_likes/js/wps_likes_script.js', array('jquery'), WPS_VERSION, true );
  }


  // Likes Actions
  public function likes_actions(){
    $whatdo = htmlspecialchars($_POST["whatdo"]);
    if ( $whatdo ){
      unset(
        $_POST["action"],
        $_POST["whatdo"]
      );
      $this->$whatdo( $_POST );
    } else {
      echo "Likes actions not found";
    }
    exit();
  }


  // clickLike 
  public function clickLike($data){
    // start
    $result = array();
    // get post ID
    $post_id = $data["post_id"];

    // get coockies
    $likes_obj = $this->getLikesCookie();

    if ( $likes_obj->$post_id ){
      unset( $likes_obj->$post_id );
      $this->removeLike($post_id);
      $result["like"] = false;
    } else {
      $likes_obj->$post_id = 1;
      $this->addLike($post_id);
      $result["like"] = true;
    }

    // get likes
    $result["count"] = get_post_meta($post_id, "wps__count__likes", true);

    $this->setToCookie( $likes_obj );

    // send result and exit
    print json_encode($result);
    exit();
  }
 

  // Add Like
  public function addLike($post_id){
    $likes = get_post_meta($post_id, "wps__count__likes", true);
    $likes++;
    update_post_meta($post_id, "wps__count__likes", $likes);
  }


  // Remove Like 
  public function removeLike($post_id){
    $likes = get_post_meta($post_id, "wps__count__likes", true);
    $likes--;
    if ( $likes <= 0 ){
      $likes = 0;
    }
    update_post_meta($post_id, "wps__count__likes", $likes);
  }


  // Clear post likes
  public function clearAllLikes($post_type){
    $count_key = 'wps__count__likes';
    $posts = get_posts([
      'post_type'   => $post_type,
      'post_status' => 'publish',
      'numberposts' => -1
    ]);
    foreach ($posts as $key => $post) {
      update_post_meta( $post->ID, $count_key, 0 );
    }
    wp_reset_postdata();
  }


  // Render Active Likes
  public static function renderActiveLikes($post_id){
    $likes_obj = self::getLikesCookie();
    if ( $likes_obj->$post_id ){
      echo "active";
    } 
  }


  // Get Count Likes Post
  public static function getCountLikesPost($post_id){
    return get_post_meta($post_id, "wps__count__likes", true) ? get_post_meta($post_id, "wps__count__likes", true) : 0;
  }


  // Coockie
  private function setToCookie($data){
    setcookie( "wps__likes", json_encode($data), time()+3600*24*365, '/', "");
  }

  public static function getLikesCookie(){
    return json_decode(html_entity_decode(stripslashes($_COOKIE["wps__likes"]), ENT_QUOTES,'UTF-8'));
  }

  public function removeLikesCookie(){
    setcookie("wps__likes", json_encode(), false, '/', "");
    unset($_COOKIE[ 'wps__likes']);
  }

}
new WPS_Likes();