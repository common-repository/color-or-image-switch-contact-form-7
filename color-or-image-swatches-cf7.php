<?php
/**
*Plugin Name: Color or Image Switch Contact Form 7
*Description: This plugin allows create Color or Image Swatches Contact Form 7.
* Version: 1.0
* Author: Ocean Infotech
* Author URI: https://www.xeeshop.com
* Copyright: 2019 
*/

if (!defined('ABSPATH')) {
  die('-1');
}
if (!defined('CISCF7_PLUGIN_NAME')) {
  define('CISCF7_PLUGIN_NAME', 'Color or Image Swatches Contact Form 7');
}
if (!defined('CISCF7_PLUGIN_VERSION')) {
  define('CISCF7_PLUGIN_VERSION', '1.0.0');
}
if (!defined('CISCF7_PLUGIN_FILE')) {
  define('CISCF7_PLUGIN_FILE', __FILE__);
}
if (!defined('CISCF7_PLUGIN_DIR')) {
  define('CISCF7_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('CISCF7_DOMAIN')) {
  define('CISCF7_DOMAIN', 'ciscf7');
}

if (!class_exists('CISCF7')) {

  class CISCF7 {

    protected static $CISCF7_instance;

    //Load all includes files
    function includes() {
      include_once('admin/switchcheckbox.php');
      include_once('admin/switchajax.php');
    }



    function init() {
      add_action( 'admin_init', array($this, 'CISCF7_load_plugin'), 11 );
      add_action('admin_enqueue_scripts', array($this, 'CISCF7_load_admin_script_style'));
      add_action( 'wp_enqueue_scripts',  array($this, 'CISCF7_load_script_style'));
      add_action( 'admin_enqueue_scripts', array($this, 'load_media_files'));
    }

    function load_media_files() {
      wp_enqueue_media();
    }
    
	  function CISCF7_load_plugin() {
      if ( ! ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) ) {
        add_action( 'admin_notices', array($this,'CISCF7_install_error') );
      }
    }

    function CISCF7_install_error() {
      deactivate_plugins( plugin_basename( __FILE__ ) );
      ?>
        <div class="error">
          <p>
            <?php _e( ' cf7 calculator plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=contact+form+7">Contact Form 7</a> plugin installed and activated.', CISCF7_DOMAIN ); ?>
          </p>
        </div>
      <?php
    }

    function CISCF7_load_admin_script_style() {
      wp_enqueue_script( 'CISCF7_back-js', CISCF7_PLUGIN_DIR . '/includes/js/back.js', false, '1.0.0' );
      wp_localize_script( 'CISCF7_back-js', 'ajax_url', admin_url('admin-ajax.php?action=image_ajax') );
      wp_enqueue_script( 'CISCF7pmfcf-wp-media-uploader', CISCF7_PLUGIN_DIR .'/includes/js/wp_media_uploader.js', false, '1.0.0' );
    }

    //Add JS and CSS on Frontend
    function CISCF7_load_script_style() {
      wp_enqueue_script( 'CISCF7-front-js', CISCF7_PLUGIN_DIR . '/includes/js/front.js', false, '1.0.0' );
      wp_enqueue_style( 'CISCF7-front-css', CISCF7_PLUGIN_DIR . '/includes/css/front-style.css', false, '1.0.0' );
    }

    //Plugin Rating
    public static function do_activation() {
      set_transient('ocswitcher-first-rating', true, MONTH_IN_SECONDS);
    }

    public static function CISCF7_instance() {
      if (!isset(self::$CISCF7_instance)) {
        self::$CISCF7_instance = new self();
        self::$CISCF7_instance->init();
        self::$CISCF7_instance->includes();
      }
      return self::$CISCF7_instance;
    }

  }
  add_action('plugins_loaded', array('CISCF7', 'CISCF7_instance'));
  register_activation_hook(CISCF7_PLUGIN_FILE, array('CISCF7', 'do_activation'));
}
