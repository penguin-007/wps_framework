<?php
/**
 * MAIL Module.
 *
 * @package   WPS_Framework
 * @version   1.0.0
 * @author    Alexander Laznevoy 
 * @copyright Copyright (c) 2017, Alexander Laznevoy
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
*/


class WPS_Mail {

  // main options module
  private $options; 

  function __construct() {
    $this->options = get_option('wps_mail_module_settings');

    // init Setting
    require_once 'dashboard/settings.php';

    // name form
    add_action( 'wp_ajax_nopriv_wps_form_send', array( $this, 'wps_form_send') );
    add_action( 'wp_ajax_wps_form_send', array( $this, 'wps_form_send') );
    // init_script
    add_action( 'wp_enqueue_scripts', array($this,'init_script') );
    // выводить интерфейс почты в админку?
    if ( $this->options['mail_in_admin_panel'] ){
      $this->wps_init_mail_type();
    }
  }
  
  // init_script
  public function init_script(){
    wp_enqueue_script  ( 'wps_mail_action', trailingslashit( WPS_EXTENSIONS_URI ) . 'wps_mail/wps_mail_action.min.js', array('jquery'), WPS_VERSION, true );
    wp_localize_script ( 'wps_mail_action', 'theme_ajax',
      array(
        'url' => admin_url('admin-ajax.php')
      )
    );
  } 


  ## wps_form_send
  public function wps_form_send(){
    // email
    $email_path = $_POST["email_path"] ? htmlspecialchars( $_POST["email_path"] ) : NULL;
    if ( $this->get_email( $email_path ) ){
      $to = $this->get_email( $email_path );
    } else {
      exit( "Не указана почта или указана неверно!" );
    }
    /* base */
    $sender       = 'wordpress@' . wps__get_sitename();
    $project_name = wps__get_sitename()." ";
    /* other */
    $subject       = $_POST["form_subject"] ? htmlspecialchars( $_POST["form_subject"] ) : "No subject";
    /* msg */
    $message = $this->render_message( $_POST );
    // save data 
    $this->wps_save_mail( "wps_mail", $subject, $message );
    /* send */
    $result = $this->send_email(array(
      "to"            => $to,
      "from"          => $project_name,
      "sender"        => $sender,
      "subject"       => $subject,
      "message"       => $message,
      "attachments"   => $_FILES,
    ));
    /* result */
    exit ( $result );
  }


  // send_email
  public function send_email( $args ){
    // defaults settings
    $to            = $args["to"];
    $from          = $args["from"];
    $sender        = $args["sender"];
    $subject       = $args["subject"];
    $message       = $args["message"];
    $attachments   = $args["attachments"];
    // генерируем разделитель
    $end      = "\r\n";
    $boundary = "--".md5(uniqid(time())); 
    // разделитель указывается в заголовке в параметре boundary 
    $headers  = "MIME-Version: 1.0;" . $end; 
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"" . $end; 
    $headers .= "From: $from <$sender>" . $end; 
    $headers .= "Reply-To: $sender" . $end; 
    // subject in utf8
    $subject  = "=?utf-8?B?".base64_encode($subject)."?=";
    // message
    $message_all  = "--$boundary" . $end; 
    $message_all .= "Content-Type: text/html; charset=utf-8" . $end;
    $message_all .= "Content-Transfer-Encoding: base64" . $end;    
    $message_all .= $end;
    $message_all .= chunk_split(base64_encode($message));

    // if attachments
    if( is_array($attachments) && !empty($attachments) ) {
      foreach ($attachments as $key => $attachment) {
        if( !empty($attachment['tmp_name']) ) {
          $filename  = $attachment['name'];
          $file      = $attachment['tmp_name'];
          $file_size = filesize($file);
          $handle    = fopen($file, "r");
          $content   = fread($handle, $file_size);
          fclose($handle);
          $message_part  = $end . "--$boundary" . $end; 
          $message_part .= "Content-Type: application/octet-stream; name=\"$filename\"" . $end;  
          $message_part .= "Content-Transfer-Encoding: base64" . $end; 
          $message_part .= "Content-Disposition: attachment; filename=\"$filename\"" . $end; 
          $message_part .= $end;
          $message_part .= chunk_split(base64_encode($content));
          $message_part .= $end . "--$boundary--" . $end;
          $message_all  .= $message_part;
        }
      }
    }

    // send
    $result = array(); // need for js "data.result"
    wp_mail( $to, $subject, $message_all, $headers );
    return json_encode($result);
  }


  // send_email for SMTP
  public function send_email__smtp($args){
  	add_action( 'phpmailer_init', array( $this, 'smtp_config') ); 
    // defaults settings
    $to            = $args["to"];
    $from          = $args["from"];
    $sender        = $args["sender"];
    $subject       = $args["subject"];
    $message       = $args["message"];
    /* header */
    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
    $headers .= 'From: ' . $from . '<'. $sender . '>';
    // send
    return wp_mail( $to, $subject, $message, $headers );
  }


  /* smtp_config
	define( 'WPS__SMTP_HOST',   '' ); // The hostname of the mail server
	define( 'WPS__SMTP_USER',   '' ); // Username to use for SMTP authentication
	define( 'WPS__SMTP_PASS',   '' ); // Password to use for SMTP authentication
	define( 'WPS__SMTP_FROM',   '' ); // SMTP From email address
	define( 'WPS__SMTP_PORT',   '25' );  // SMTP port number - likely to be 25, 465 or 587
	define( 'WPS__SMTP_SECURE', 'tls' ); // Encryption system to use - ssl or tls
	*/
	public function smtp_config( $phpmailer ) {
		$phpmailer->isSMTP();
		$phpmailer->Host       = WPS__SMTP_HOST;
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Port       = WPS__SMTP_PORT;
		$phpmailer->Username   = WPS__SMTP_USER;
		$phpmailer->Password   = WPS__SMTP_PASS;
		$phpmailer->SMTPSecure = WPS__SMTP_SECURE;
		$phpmailer->From       = WPS__SMTP_FROM;
    // Always remove self at the end
    remove_action( 'phpmailer_init', __function__ );
	}


  /* get_email */
  private function get_email( $email_path ){
    if ( $email_path != NULL ) {
      $email = $this->options[$email_path];
    } 
    if( !$email ){
      $email = $this->options['theme_email'];
    }
    if ( !$email ){
      return false;
    }
    return $email;
  }


  /* render_message */
  public function render_message( $post ){
    if ( !is_array( $post ) ) exit();
    // defaults
    $path       = "template/";
    $template   = "default";
    $form_title = "";
    // check 
    if ( isset( $post["form_template"] ) && $post["form_template"] != "" ){
      $path     = CHILD_DIR."/wps_config/mail_template/";
      $template = htmlspecialchars( $post["form_template"] );
    }
    if ( isset( $post["form_title"] ) && $post["form_title"] != "" ){
      $form_title = htmlspecialchars( $post["form_title"] );
    }
    /* clear post */
    unset(
      $post['action'],
      $post['form_subject'], 
      $post['form_redirect'], 
      $post['form_title'],
      $post['email_path'],
      $post['form_template']
    );
    /* msg */
    ob_start();
      require( "{$path}{$template}.php" );
      $message .= ob_get_contents();
    ob_end_clean();

    return $message;
  }


  private function wps_init_mail_type(){

    ################ Mail Type ###############
    new WPS_CustomType(
      array(
        /* Create Files */
        'create_archive_file' => false,
        'create_single_file'  => false,

        /* Post Type Register */
        'register_post_type' => array(
          'post_type' => 'wps_mail', // 1) custom-type name
          // labels
          'labels'    => array(
            'name'          => 'Mail',
            'singular_name' => 'Mail', 
            'menu_name'     => 'Mail'
          ),
          // supports_label
          'supports_label' => array(
            'title',
          ),
          // rewrite
          'rewrite' => array(
            'slug'         => 'wps_mail', // 2) custom-type slug
            'with_front'   => false,
            'hierarchical' => true
          ),
          // general
          'general' => array(
            /* if need remove in query */
            'query_var'           => false, 
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'taxonomies'          => array('wps_mail_tax'), // 3) 
            'menu_icon'           => 'dashicons-email-alt', 
          )
        ),

        ################ Mail Tax ###############
        'register_taxonomy' => array(
          // tax
          array (
            'taxonomy_name' => 'wps_mail_tax',         // 1) 
            'setting' => array(
              'label'              => 'Категория', // 2) 
              'hierarchical'       => true,
              'publicly_queryable' => false,
              'public'             => false,
              'query_var'          => false,
              'show_admin_column'  => true, 
              'show_ui'            => true 
            )
          ),
          // tax
        )

      )
    );

    ################ Mail Meta Fields ###############
    new WPS_MetaBox(
      array(
        'meta_box_name'   => 'Почта',                   
        'post_types'      => array( 'wps_mail' ),   
        'page_templates'  => array(  ),
        'meta_box_groups' => array(
          // GROUP FIELD
          array(
            'title'    => '',
            'fields'   => array(
              array(
                'field_type'   => 'input',
                'field_name'   => 'counter',
                'title'        => '№ письма',
                'type_input'   => 'number',
              ),
              array(
                'field_type'  => 'wp_editor',
                'field_name'  => 'post_data',
                'title'       => 'Содержание письма',
                'description' => '',
              ),
              array(
                'field_type'   => 'checkbox',
                'field_name'   => 'viewed',
                'title'        => 'Просмотрено',
              ),
            )
          ),
          // GROUP FIELD
        )
      )
    );

    ################ Mail Post Columns ###############
    new WPS_PostColumns(
      array(
        'post_type' => 'wps_mail',
        'fields'    => array(
          array(
            'field_type'   => 'text',
            'field_name'   => 'counter',
            'columns_name' => '№ письма'
          ),
          array(
            'field_type'   => 'checkbox',
            'field_name'   => 'viewed',
            'columns_name' => 'Просмотрено'
          ),
          array(
            'field_type'   => 'row_color',
            'field_name'   => 'viewed',
            'columns_name' => 'Row Color',
            'options'      => array(
              "on" => "rgba(130, 218, 185, 0.1)",
              ""   => "rgba(255, 90, 90, 0.05)",
            )
          ),
        )
      )
    );

  }


  /* wps_save_mail */
  public static function wps_save_mail( $post_type = "wps_mail", $subject, $mail_data ){

    /* mail number */
    $cur_count = get_option( "wps_mail_counter__{$subject}" );
    $new_count = !$cur_count ? (int) 1 : ++$cur_count;
    update_option(  "wps_mail_counter__{$subject}", $new_count );

    /* set meil to admin */
    $order_detail = array(
      'post_title'   => $subject,
      'post_type'    => $post_type,
      'post_status'  => 'publish',
      'meta_input'   => array(
        'counter'   => $new_count,
        'post_data' => $mail_data, 
      )
    );
    $post_id = wp_insert_post( $order_detail );

    wp_set_object_terms( $post_id, $subject, 'wps_mail_tax', false ); // false - перезаписать старые связи

  }

}

new WPS_Mail();