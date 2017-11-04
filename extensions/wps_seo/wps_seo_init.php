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

    $this->add_field_tax();
    $this->add_field_post_archives();
    $this->add_field_post_types();

    ## SET TITLE
    add_action( 'pre_get_document_title', array( $this, 'wps_set_seo_title' ), 1 );

    ## SET DESCRIPTION
    add_action( 'wp_head', array( $this, 'wps_revrite_description' ), 1 );
  }


  function render_menu(){

    // get_post_types
    $options_post_types = array(
      "page" => "Page"
    );
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

    // get_taxonomies
    $options_taxonomies = array();
    $taxonomies = get_taxonomies( $args, 'object', 'and' );
    foreach( $taxonomies as $taxonomy ){
      $options_taxonomies[$taxonomy->name] = $taxonomy->labels->singular_name;
    }

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
            'field_type'   => 'message', 
            'message'      => 'Находится в стадии переработки',
          ),
          array(
            'field_type'   => 'select',
            'field_name'   => 'select_post_types',
            'title'        => 'Использовать для типов записей:',
            'multiple'     => true,
            'options'      => $options_post_types
          ),
          array(
            'field_type'   => 'select',
            'field_name'   => 'select_post_archives',
            'title'        => 'Использовать для архивов типов записей:',
            'multiple'     => true,
            'options'      => $options_post_archives
          ),
          array(
            'field_type'   => 'select',
            'field_name'   => 'select_taxonomies',
            'title'        => 'Использовать для таксономий:',
            'multiple'     => true,
            'options'      => $options_taxonomies
          ),

        )
      )
    );
  }


  function add_field_tax(){
    // добавим поля к таскономиям
    // Мета теги Title, Description и область для СЕО текста
    // Скроем поле "описание", которое могут использовать другие СЕО плагины
    if ( isset( $this->config['select_taxonomies'] ) && is_array( $this->config['select_taxonomies'] ) ) :
    new WPS_TermFields( 
      array(
        'taxonomy'  => $this->config['select_taxonomies'],
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
            'field_type'  => 'wp_editor',
            'field_name'  => 'wps_seo__text',
            'title'       => 'SEO Text',
            'options' => array(
              'media_buttons' => 1,
            )
          ),
          array(
            'field_type'  => 'hide_block',
            'block_cont'  => '<style>.term-description-wrap{display:none;}</style>',
          ),
        )
      )
    );
    endif;
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

  function add_field_post_types(){
    // поля для типов записей
    if ( isset( $this->config['select_post_types'] ) && is_array( $this->config['select_post_types'] ) ) :
    new WPS_MetaBox(
      array(
        'meta_box_name'   => 'WPS SEO',                  
        'post_types'      => $this->config['select_post_types'],
        'meta_box_groups' => array(
          // GROUP FIELD
          array(
            'title'    => '',
            'fields'   => array(
              array(
                'field_type'  => 'input',
                'field_name'  => 'seo_post_title',
                'title'       => 'Title',
              ),
              array(
                'field_type'  => 'textarea',
                'field_name'  => 'seo_post_description',
                'title'       => 'Description',
              ),
              array(
                'field_type'  => 'wp_editor',
                'field_name'  => 'seo_post_seo_text',
                'title'       => 'SEO Text',
                'options' => array(
                  'media_buttons' => 1,
                )
              ),

            )
          ),
          // GROUP FIELD
        )
      )
    );
    endif;
  }


  ## SET TITLE
  function wps_set_seo_title(){
    // if is single page or page
    if ( is_singular() ){
      global $post;
      return get_post_meta( $post->ID, 'seo_post_title', true );
    }

    // if is page acrhive custom post
    if ( is_post_type_archive() ){ 
      $post_type    = get_query_var('post_type');
      $setting_name = "wps_seo__type_{$post_type}";
      $seo_options  = get_option( $setting_name );
      return $seo_options["wps_seo__title"];
    }
    
    // if is page taxonomy
    if ( is_tax() ){
      $cur_cat_obj  = get_queried_object();
      $term_id      = $cur_cat_obj->term_id;
      return get_term_meta( $term_id, 'wps_seo__title', true );
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

    // if is single page or page
    if ( is_singular() ){
      global $post;
      return get_post_meta( $post->ID, 'seo_post_description', true );
    }

    // if is page acrhive custom post
    if ( is_post_type_archive() ){  
      $post_type    = get_query_var('post_type');
      $setting_name = "wps_seo__type_{$post_type}";
      $seo_options  = get_option( $setting_name );
      return $seo_options["wps_seo__description"];
    }
    
    // if is page taxonomy
    if ( is_tax() ){
      $cur_cat_obj  = get_queried_object();
      $term_id      = $cur_cat_obj->term_id;
      return get_term_meta( $term_id, 'wps_seo__description', true );
    }

  }



  ## SET SEO TEXT
  public static function wps__get_seo_text(){

    // if is single page or page
    if ( is_singular() ){
      global $post;
      return get_post_meta( $post->ID, 'seo_post_seo_text', true );
    }

    // if is page acrhive custom post
    if ( is_post_type_archive() ){
      $post_type    = get_query_var('post_type');
      $setting_name = "wps_seo__type_{$post_type}";
      $seo_options  = get_option( $setting_name );
      return $seo_options["wps_seo__text"];
    }
   
    // if is page taxonomy
    if ( is_tax() ){ 
      $cur_cat_obj  = get_queried_object();
      $term_id      = $cur_cat_obj->term_id;
      return get_term_meta( $term_id, 'wps_seo__text', true );
    }

  }

}

new WPS_SEO();