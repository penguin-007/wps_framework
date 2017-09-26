<?php
/**
 * Functions for admin panel.
 *
 * @package    WPS_Framework
 * @subpackage Functions
 * @author     Alexander Laznevoy 
 * @copyright  Copyright (c) 2017, Alexander Laznevoy
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


#### Hide top menu item 
add_action( 'wp_before_admin_bar_render', 'wps_admin_bar' );
function wps_admin_bar() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('comments');
  $wp_admin_bar->remove_menu('new-content');
  $wp_admin_bar->remove_menu('about');
  $wp_admin_bar->remove_menu('wporg');
  $wp_admin_bar->remove_menu('documentation');
  $wp_admin_bar->remove_menu('support-forums');
  $wp_admin_bar->remove_menu('feedback');
  $wp_admin_bar->remove_menu('view-site');
}



#### Hide menu item
add_action( 'admin_menu', 'wps_remove_menus' );
function wps_remove_menus(){
  remove_menu_page( 'edit.php' );                   //Записи
  //remove_menu_page( 'index.php' );                  //Консоль
  //remove_menu_page( 'upload.php' );                 //Медиафайлы
  //remove_menu_page( 'edit.php?post_type=page' );    //Страницы
  //remove_menu_page( 'edit-comments.php' );          //Комментарии
  //remove_menu_page( 'themes.php' );                 //Внешний вид
  //remove_menu_page( 'plugins.php' );                //Плагины
  //remove_menu_page( 'users.php' );                  //Пользователи
  //remove_menu_page( 'tools.php' );                  //Инструменты
  //remove_menu_page( 'options-general.php' );        //Настройки
}


#### Admin footer modification
add_filter('admin_footer_text', 'wps_remove_footer_admin');
function wps_remove_footer_admin() {
  echo '<span id="footer-thankyou">Theme use WPS Framework v'.WPS_VERSION.'</span>';
}


#### Save data with CTRL + S
add_filter('admin_footer', 'post_save_accesskey');
function post_save_accesskey(){
  if( get_current_screen()->parent_base != 'edit' ) return;
  ?>
  <script type="text/javascript">
  jQuery(document).ready(function($){
    $(window).keydown(function(e){
      // событие ctrl+s - 83 код s
      if( e.ctrlKey && e.keyCode == 83 ){
        e.preventDefault();
        // for post & page
        $('[name="save"]').click();
        // for terms
        $('input.button-primary').click();
      }
    });
  });
  </script>
  <?php
}
add_action('admin_notices', 'general_admin_notice');
function general_admin_notice(){
  global $pagenow;
  if ( $pagenow == 'post.php' || $pagenow == 'term.php' ) {
    echo '
    <div class="notice notice-info is-dismissible">
      <p>Для сохранения доступна комбинация "CTRL + S".</p>
    </div>';
  }
}