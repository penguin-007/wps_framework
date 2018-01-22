<?php
/**
 * SEO Module.
 * Add fields for meta tags in post_types, post_archive, terms.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * 
 * @todo Markup (JSON-LD) structured in schema.org
 *
*/


class WPS_SEO {

  private $config;

  function __construct( $args = array() ) {
    $this->config = get_option( "wps_framework__module_seo" );

    add_action( 'wp_loaded', array( $this, 'render_menu' ), 1 );

    $this->add_field_post_archives();

    ## SET TITLE
    add_action( 'pre_get_document_title', array( $this, 'wps_set_seo_title' ), 1 );

    ## SET DESCRIPTION
    add_action( 'wp_head', array( $this, 'wps_revrite_description' ), 1 );

    ## SET KEYWORDS
    add_action( 'wp_head', array( $this, 'wps_revrite_keywords' ), 1 );

    /* disable all seo pack for archive pages */
    add_filter( 'aiosp_disable', array( $this, 'disable_aioseop'), 10, 1 );
  }


  function render_menu(){

    $args = array(
      'public'    => true,
      '_builtin'  => false,
      'query_var' => true
    );

    $post_types = get_post_types( $args, 'object', 'and' );
    foreach( $post_types as $post_type ){
      $options_post_types[$post_type->name] = $post_type->labels->singular_name;
    }

    // post archives
    $options_post_archives = $options_post_types;
    unset( $options_post_archives["page"] );


    new WPS_OptionPage(
      array(
        /* submenu_setting */
        'submenu_setting' => array(
          'submenupos' => 'wps_framework',
          'page_title' => 'SEO Module',
          'menu_title' => 'SEO Module',
          'capability' => 'administrator',
          'menu_slug'  => 'wps_framework__module_seo',
        ),
        /* submenu_setting */
        'fields'    => array(
          array(
            'field_type'   => 'select',
            'field_name'   => 'select_post_archives',
            'title'        => 'Использовать для архивов типов записей:',
            'multiple'     => true,
            'options'      => $options_post_archives
          ),
        )
      )
    );
  }



  function add_field_post_archives(){
    // Добавим подменю для страниц архивов
    if ( isset( $this->config['select_post_archives'] ) && is_array( $this->config['select_post_archives'] ) ) :
    foreach ( $this->config['select_post_archives'] as  $value) {
      new WPS_OptionPage(
        array(
          /* submenu_setting */
          'submenu_setting' => array(
            'submenupos' => "edit.php?post_type={$value}",
            'page_title' => 'SEO Archive',
            'menu_title' => 'SEO',
            'capability' => 'administrator',
            'menu_slug'  => "wps_seo__type_{$value}",
          ),
          /* submenu_setting */
          'fields'    => array(

            array(
              'field_type'  => 'input',
              'field_name'  => 'wps_seo__title',
              'title'       => 'Meta Title',
            ),
            array(
              'field_type'  => 'textarea',
              'field_name'  => 'wps_seo__description',
              'title'       => 'Meta Description',
            ),
            array(
              'field_type'  => 'textarea',
              'field_name'  => 'wps_seo__keywords',
              'title'       => 'Meta Keywords',
            ),
            array(
              'field_type'  => 'wp_editor',
              'field_name'  => 'wps_seo__text',
              'title'       => 'SEO Text',
              'options' => array(
                'media_buttons' => 1,
              )
            ),

          )
        )
      );
    }
    endif;
  }



  ## SET TITLE
  function wps_set_seo_title(){
    // if is page acrhive custom post
    if ( is_post_type_archive() ){ 
      $post_type    = get_query_var('post_type');
      $setting_name = "wps_seo__type_{$post_type}";
      $seo_options  = get_option( $setting_name );
      return $seo_options["wps_seo__title"];
    }
  }


  ## SET DESCRIPTION
  function wps_revrite_description(){
    $description = $this->wps_set_seo_description();
    if ( $description != '' ){
      echo "<!-- WPS__Description -->\r\n";
      echo "<meta name='description' content='".$description."' />\r\n";
    }
  }

  function wps_set_seo_description(){
    // if is page acrhive custom post
    if ( is_post_type_archive() ){  
      $post_type    = get_query_var('post_type');
      $setting_name = "wps_seo__type_{$post_type}";
      $seo_options  = get_option( $setting_name );
      return $seo_options["wps_seo__description"];
    }
  }


  ## SET KEYWORDS
  function wps_revrite_keywords(){
    $description = $this->wps_set_seo_keywords();
    if ( $description != '' ){
      echo "<!-- WPS__Keywords -->\r\n";
      echo "<meta name='keywords' content='".$description."' />\r\n";
    }
  }

  function wps_set_seo_keywords(){
    // if is page acrhive custom post
    if ( is_post_type_archive() ){  
      $post_type    = get_query_var('post_type');
      $setting_name = "wps_seo__type_{$post_type}";
      $seo_options  = get_option( $setting_name );
      return $seo_options["wps_seo__keywords"];
    }
  }



  ## SET SEO TEXT
  public static function wps__get_seo_text(){
    // if is page acrhive custom post
    if ( is_post_type_archive() ){
      $post_type    = get_query_var('post_type');
      $setting_name = "wps_seo__type_{$post_type}";
      $seo_options  = get_option( $setting_name );
      return $seo_options["wps_seo__text"];
    }
  }


  /* disable all seo pack for archive pages */
  public function disable_aioseop( $disable ){
    if ( is_post_type_archive() ){
       return true; 
    }
    return false;
  }

}

new WPS_SEO();