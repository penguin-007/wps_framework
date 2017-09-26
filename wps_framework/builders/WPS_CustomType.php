<?php
/**
 * New Post Type Builder Class.
 * Create post type, taxonomies, file archive & single
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


/* HOW USE
new WPS_CustomType(
  array(
    ## Create Files
    'create_archive_file' => false,
    'create_single_file'  => false,

    ## Post Type Register
    'register_post_type' => array(
      'post_type' => 'custom_post2', // 1) custom-type name
      // labels
      'labels'    => array(
        'name'          => 'PostType2',
        'singular_name' => 'PostType2', 
        'menu_name'     => 'PostType2'
      ),
      // supports_label
      'supports_label' => array(
        'title',
        //'thumbnail', 
        'editor',
        //'custom-fields',
      ),
      // rewrite
      'rewrite' => array(
        'slug'         => 'custom_post2', // 2) custom-type slug
        'with_front'   => false,
        'hierarchical' => true
      ),
      // general
      'general' => array(
        // if need remove in query
        //'query_var'           => false, 
        //'publicly_queryable'  => false,
        //'exclude_from_search' => true,
        'taxonomies'        => array('name_cat'), // 3) 
        'menu_icon'         => 'dashicons-star-empty', // 4) https://developer.wordpress.org/resource/dashicons/
      )
    ),

    ## Create Taxonomy 
    'register_taxonomy' => array(
      // tax
      array (
        'taxonomy_name' => 'name_cat',         // 1) 
        'setting' => array(
          'label'             => 'Таксономия', // 2) 
          'hierarchical'      => true,
          'public'            => true,
          'query_var'         => true,
          'rewrite'           => array( 
            'slug'         => 'name_cat',      // 3)
            'with_front'   => true,
            'hierarchical' => true
          ),
          'show_admin_column' => true, 
          'show_ui'           => true 
        )
      ),
      // tax
    )

  )
);
*/
 
class WPS_CustomType {

  private $options;
  
  private $post_type;

  function __construct( $option ) {
    // get all options
    $this->options = (object) $option;
    // get options post-type
    $this->post_type = $this->options->register_post_type['post_type'];

    ################ Post Type Register ################
    add_action( 'init',	array( $this, 'register_post_type' ) );

    ################ Create Taxonomy ###################
    add_action( 'init', array( $this, 'create_taxonomy' ) );

    ################ File Creator ######################
    if ( $this->options->create_archive_file ){
      $this->create_post_archive( $this->post_type );
    }
    if ( $this->options->create_single_file ){
      $this->create_post_single( $this->post_type );
    }

  }


  ####################################################
  ################ Post Type Register ################
  ####################################################
  public function register_post_type() {
    // get options post-type
    $post_type_options = $this->options->register_post_type;

    // labels
    $labels_defaults = array(
      'add_new'       => 'Добавить',
      'add_new_item'  => 'Добавить',
      'edit_item'     => 'Изменить',
      'new_item'      => 'Новый',
      'all_items'     => 'Все',
      'view_item'     => 'Посмотреть на сайте',
      'search_items'  => 'Найти',
      'not_found'     => 'Не найдено.',
    );
    $labels = $post_type_options['labels'];
    $labels = wp_parse_args( $labels, $labels_defaults );

    // other
    $supports_label = $post_type_options['supports_label'];
    $rewrite        = $post_type_options['rewrite'];

    // general
    $general_defaults = array(
      'public'            => true,
      'show_ui'           => true,
      'has_archive'       => true,
      'capability_type'   => 'post',
      'show_in_menu'      => true,
      'query_var'         => true, 
      'show_in_nav_menus' => true,
    );
    $general = $post_type_options['general'];
    $general = wp_parse_args( $general, $general_defaults );

    // common
    $args = array(
      'labels'   => $labels,
      'supports' => $supports_label,
      'rewrite'  => $rewrite
    );

    $params = $args + $general;
    register_post_type( $this->post_type, $params );
  }


  ####################################################
  ############### Create Taxonomy ####################
  ####################################################
  public function create_taxonomy() {
    // get options post-type
    $taxonomy_options = $this->options->register_taxonomy;

    if ( $taxonomy_options ) {
      foreach( $taxonomy_options as $value ) {
        register_taxonomy(
          $value['taxonomy_name'],
          $this->post_type,
          $value['setting']
        );
      }
    }
  }


  ####################################################
  ################### File Creator ###################
  ####################################################
  public function create_post_archive( $post_type ){
    $post_archive = PARENT_DIR . "/archive-{$post_type}.php";
    if ( !file_exists($post_archive) ) {
      $fp      = fopen( $post_archive, "w");
      $content = "<?php get_header(); ?>\n\n<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>\n\n<?php endwhile; else: ?>\n <p><?php _e(''); ?></p>\n<?php endif; ?>\n\n<?php get_footer(); ?>";
      fwrite($fp, $content);
      fclose($fp);
    }
  }

  public function create_post_single( $post_type ){
    $post_archive = PARENT_DIR . "/single-{$post_type}.php";
    if ( !file_exists($post_archive) ) {
      $fp      = fopen( $post_archive, "w");
      $content = "<?php get_header(); ?>\n\n<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>\n\n<?php endwhile; else: ?>\n <p><?php _e(''); ?></p>\n<?php endif; ?>\n\n<?php get_footer(); ?>";
      fwrite($fp, $content);
      fclose($fp);
    }
  }

}

