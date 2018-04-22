<?php
session_start();

class Users
{
    private static $framework_options;
    private static $defaults = array (
        "wps__example_config" => false,
        'wps__extends_mail'   => false,
        'wps__extends_seo'    => false,
        'wps__extends_cart'   => false,
        'wps__extends_likes'  => false,
        'wps__extends_tinymc' => true,
        'wps__shortcodes'     => true,
        'wps__users'        => false,
    );
    public function __construct()
    {
        self::$framework_options = wp_parse_args( get_option('wps_framework_options'), self::$defaults);

        require_once 'dashboard/settings.php';

        add_action( 'wp_enqueue_scripts', array(Users::class,'initScript') );
        /*Регистрация*/
        add_action('wp_ajax_nopriv_addUser', array( Users::class,'addUser' ));
        add_action('wp_ajax_addUser', array( Users::class,'addUser'));
        /* Верификация */
        add_action('wp_ajax_nopriv_setVerify', array( Users::class,'setVerify' ));
        add_action('wp_ajax_setVerify', array( Users::class,'setVerify'));
        /* авторизация пользователя */
        add_action('wp_ajax_nopriv_authUser', array( Users::class,'authUser' ));
        add_action('wp_ajax_authUser', array( Users::class,'authUser'));
        /* авторизация / регистрация через соц сети */
        add_action('wp_ajax_nopriv_socialAuth', array( Users::class,'socialAuth' ));
        add_action('wp_ajax_socialAuth', array( Users::class,'socialAuth'));
        /*генераципя нового кодя для смс и его отправка*/
        add_action('wp_ajax_nopriv_reGenerateCode', array( Users::class,'reGenerateCode' ));
        add_action('wp_ajax_reGenerateCode', array( Users::class,'reGenerateCode'));
    }

    public static function initScript()
    {
        wp_enqueue_script( 'croppie', trailingslashit( PARENT_URI ) . 'assets/libs/croppie/croppie.min.js', array('jquery'), WPS_VERSION, true );
        wp_enqueue_style ( 'croppie',  trailingslashit( PARENT_URI ) . 'assets/libs/croppie/croppie.css', array(), WPS_VERSION, null );

        wp_enqueue_script( 'ulogin', '//ulogin.ru/js/ulogin.js', array('jquery'), '1.0.0', true );

        wp_enqueue_script  ( 'wps_users_actions', trailingslashit( WPS_EXTENSIONS_URI ) . 'wps_users/wps_users_actions.js', array('jquery'), WPS_VERSION, true );
    }

    public static function addUser($return = false)
    {
        $results = array();
        if(is_string($_POST['data'])) {
            parse_str($_POST['data'],$raw_data);
        } else {
            $raw_data = $_POST['data'];
        }
        $stripped_data = array();
        foreach ($raw_data as $field => $value) {
            if($field == 'user_phone') {
                $value = preg_replace('/[^\d]/', '',$value);
            }
            $stripped_data[$field] = wp_slash(trim(strip_tags($value)));
        }

        $args = array(
            'post_type' => 'users',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'user_email',
                    'value' => $stripped_data['user_email'],
                    'compare' => '=',
                    'posts_per_page' => 1,
                ),
                array(
                    'key' => 'user_phone',
                    'value' => $stripped_data['user_phone'],
                    'compare' => '='
                )
            )
        );
        $query = new WP_Query($args);

        if(empty($errors = self::validateData($stripped_data)) && empty($query->post_count)) {
            $verify_code = rand(1000,9000);
            $user_meta_input = array(
                'user_password' => password_hash($stripped_data['user_password'], PASSWORD_DEFAULT),
                'user_email'    => $stripped_data['user_email'],
                'user_phone'    => $stripped_data['user_phone'],
                'user_code'   => $verify_code,
                'user_city'   => $stripped_data['user_city'],
            );
            // prepare user fields
            $user_fields = array(
                'post_title'   => $stripped_data['user_name']. ' '. $stripped_data['last_name'],
                'post_type'    => 'users',
                'post_status'  => 'publish',
                'meta_input'   => $user_meta_input,
            );
            // create user
            $post_id = wp_insert_post( $user_fields );

            if($post_id) {
                if( true == self::$framework_options['wps__extends_mail'] ) {
                    $mail = new WPS_Mail();
                    // message_data
                    $message_data["form_template"] = "register";
                    $message_data["user_code"]   = $verify_code;
                    $message_data["user_email"]    = $stripped_data['user_email'];
                    $message_data["user_name"]      = $stripped_data['user_name'];
                    $message_data["user_password"]      = $stripped_data['user_password'];

                    $message = $mail->render_message($message_data);
                    // email_data
                    $email_data['to']      = $stripped_data['user_email'];
                    $email_data['from']    = wps__get_sitename();
                    $email_data['sender']  = 'wordpress@' . wps__get_sitename();
                    $email_data['subject'] = "Регистрация";
                    $email_data['message'] = $message;
                    // send email
                    $mail->send_email($email_data);
                }
                $results['success'] = true;
                $_SESSION['user_id'] = $post_id;
            } else {
                $results['success'] = false;
            }
        } else {
            if($query->post_count > 0){
                $errors['duplicate_email'] = 'duplicate_email';
            }
            $results['success'] = false;
            $results['errors'] = $errors;
        }

        if($return) {
            return $results;
        } else {
            header("Content-type: application/json; charset=UTF-8");
            header("Cache-Control: must-revalidate");
            header("Pragma: no-cache");
            header("Expires: -1");

            // send result and exit
            print json_encode($results);
            exit();
        }
    }

    public static function getUser($user_id)
    {
        $user = false;
        if (!empty($user_id)) {
            $user = get_post($user_id);
            if ($user->post_status != 'publish') {
                unset($_SESSION['user_id']);
            }
            $meta_fields = get_post_meta($user_id, '', false);

            foreach ($meta_fields as $field => $value) {
                $user->{$field} = reset($value);
            }

        }
        return $user;
    }


    public static function updateUser($user_id,$data = array())
    {
        if(is_array($data)) {
            foreach ($data as $field => $value) {
                update_post_meta($user_id,$field,$value);
            }
        }
        return $user_id;
    }

    public static function setVerify()
    {
        $data = intval($_POST['verify_code']);
        $json_result = array();
        $user = self::getUser($_SESSION['user_id']);
        if(!empty($data)) {
            if($user->user_code == $data) {
                $update['user_verify'] = 'on';
                self::updateUser($_SESSION['user_id'],$update);
                $json_result['success'] = true;
            } else {
                $json_result['success'] = false;
            }
        }
        print json_encode($json_result);
        exit();
    }

    public static function reGenerateCode()
    {
        $json_result = array();
        $new_code = rand(1000,9000);
        $update_data['user_code'] = $new_code;
        $update_data['user_phone'] = trim(preg_replace('/[^\d]/','',$_POST['user_phone']));
        self::updateUser($_SESSION['user_id'],$update_data);
        $json_result['success'] = true;

        header("Content-type: application/json; charset=UTF-8");
        header("Cache-Control: must-revalidate");
        header("Pragma: no-cache");
        header("Expires: -1");

        // send result and exit
        print json_encode($json_result);
        exit();

    }


    /**
     * Возвращает статус авторизации пользователя
     * @return boolean
     */
    public static function isLogged()
    {
        if (isset($_POST['logout'])) {
            unset($_SESSION['user_id']);
            header('Location:' . get_site_url());
            return false;
        } elseif (!empty($_SESSION["user_id"])) {
            $user = get_post($_SESSION["user_id"]);
            if ($user->post_status != 'publish') {
                unset($_SESSION['user_id']);
                header('Location:' . get_site_url());
                return false;
            } else {
                return true;
            }
        } else {
            header('Location:' . get_site_url());
            return false;
        }
    }


    public static function authUser($return = false)
    {
        $results = array();
        $results['success'] = false;
        if(is_string($_POST['data'])) {
            parse_str($_POST['data'],$raw_data);
        } else {
            $raw_data = $_POST['data'];
        }
        $stripped_data = array();
        foreach ($raw_data as $field => $value) {
            if($field == 'user_phone') {
                $value = preg_replace('/[^\d]/', '',$value);
            }
            $stripped_data[$field] = wp_slash(trim(strip_tags($value)));
        }

        if(empty($errors = self::validateData($stripped_data))) {
            $args = array(
                'post_type' => 'users',
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'user_email',
                        'value' => $stripped_data['user_email'],
                        'compare' => '='
                    ),
                    array(
                        'key' => 'user_phone',
                        'value' => $stripped_data['user_phone'] ? $stripped_data['user_phone'] : $stripped_data['user_email'],
                        'compare' => '='
                    )
                )
            );
            $query = new WP_Query($args);
            if($query->post_count > 0) {
                $user_pass = get_post_meta($query->post->ID,'user_password', true);
                if(isset($_POST['social']) && $_POST['social'] == 1) {
                    $_SESSION['user_id'] = $query->post->ID;
                    $results['success'] = true;
                } elseif (password_verify($stripped_data['user_password'], $user_pass)) {
                    $_SESSION['user_id'] = $query->post->ID;
                    $results['success'] = true;
                } else {
                    $errors['wrong_password'] = 'wrong_password';
                    $results['errors'] = $errors;
                }
            } else {
                $errors['wrong_login'] = 'wrong_login';
                $results['errors'] = $errors;
            }
        } else {
            $results['errors'] = $errors;
        }

        if($return) {
            return $results;
        } else {
            header("Content-type: application/json; charset=UTF-8");
            header("Cache-Control: must-revalidate");
            header("Pragma: no-cache");
            header("Expires: -1");

            // send result and exit
            print json_encode($results);
            exit();
        }

    }

    public static function socialAuth()
    {
        $results = array();
        if(is_string($_POST['data'])) {
            parse_str($_POST['data'],$raw_data);
        } else {
            $raw_data = $_POST['data'];
        }
        $stripped_data = array();
        foreach ($raw_data as $field => $value) {
             if($field == 'user_phone') {
                 $value = preg_replace('/[^\d]/', '',$value);
             }
            $stripped_data[$field] = wp_slash(trim(strip_tags($value)));
        }

        $auth_results = self::authUser(true);
        if($auth_results['success']) {
            /*Все хорошо, авторизовались*/
            $results['success'] = true;
        } elseif($auth_results['errors']) {
            if(isset($auth_results['errors']['wrong_login'])) {
                $res = self::addUser(array(),true);
                if($res['success']) {
                    $results['success'] = true;
                }
                else {
                    $results['success'] = false;
                }
            }
        }

        header("Content-type: application/json; charset=UTF-8");
        header("Cache-Control: must-revalidate");
        header("Pragma: no-cache");
        header("Expires: -1");

        // send result and exit
        print json_encode($results);
        exit();
    }

    private static function validateData($data = array())
    {
        $errors = array();

        // name
        if(isset($data['user_name']) && empty($data['user_name'])) {
            $errors['empty_name'] = 'empty_name';
        }

        if(isset($data['last_name']) && empty($data['last_name'])) {
            $errors['empty_last_name'] = 'empty_last_name';
        }

        // email
        if(isset($data['user_email']) && empty($data['user_email'])) {
            $errors['empty_email'] = 'empty_email';
        }

        // phone
        if(isset($data['user_phone']) && empty($data['user_phone'])) {
            $errors['empty_phone'] = 'empty_phone';
        }

        // city
        if(isset($data['user_city']) && empty($data['user_city'])) {
            $errors['empty_city'] = 'empty_city';
        }

        // pass
        if(isset($data['user_password']) && empty($data['user_password'])) {
            $errors['empty_password'] = 'empty_password';
        }
        if(isset($data['repeat_password']) && empty($data['repeat_password'])) {
            $errors['empty_repeat_password'] = 'empty_repeat_password';
            // 2 step
            if ( $data['user_password'] !== $data['repeat_password'] ) {
                $errors['pass_repeat_error'] = 'pass_repeat_error';
            }
        }

        return $errors;
    }
}
$users = new Users();

/*Сохранение данных в ЛК*/
if(isset($_POST['save_user']) && $_POST['save_user'] == 'save_user') {

    $filename = strip_tags($_POST['user_email'].rand(1000,9000).'.png');

    if(isset($_POST['delete_avatar']) && $_POST['delete_avatar'] == 1 && !empty($_POST['user_image'])) {
        wp_delete_attachment( intval($_POST['user_image']), false );
        update_post_meta($_SESSION['user_id'],'user_avatar','' );
    }
    if(isset($_POST['user_avatar'])){
        $data = $_POST['user_avatar'];

        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);

        $wordpress_upload_dir = wp_upload_dir();
        $new_file_path = $wordpress_upload_dir['path'] . '/'.$filename;
        $new_file_mime = 'image/png';
        if( file_put_contents( $new_file_path, $data ) ) {
            $upload_id = wp_insert_attachment( array(
                'guid'           => $new_file_path,
                'post_mime_type' => $new_file_mime,
                'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
                'post_content'   => '',
                'post_status'    => 'inherit',
            ), $new_file_path );
            // wp_generate_attachment_metadata() won't work if you do not include this file
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            // Generate and save the attachment metas into the database
            wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );
            update_post_meta($_SESSION['user_id'],'user_avatar',intval($upload_id) );
        }
    }
}