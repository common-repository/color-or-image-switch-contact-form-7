<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('CISCF7_ajax')) {

  class CISCF7_ajax {

    protected static $instance;
   
    function ajax_call() {
      $number = sanitize_text_field($_REQUEST['random_number']);
      ?>
      <tr>
        <td>
          <?php  
            echo CF7POPUP_image_uploader_field( 'image', get_post_meta(101, 'hidden_img_count', true ), $number);
            if(!empty(get_post_meta(101, 'popup_image_color', true ))){ 
              ?>
              <img src="<?php echo get_post_meta(101, 'popup_image_color', true ); ?>" width="50px" height="50px">
              <?php 
            } 
          ?>
        </td>
        <td><input type="text" name="imglabels-<?php echo $number; ?>" class="option shhh" placeholder="Enter Image Label"></td>
     </tr> 
     <?php           
    }
   
        
   

   
    function init() {
      
      add_action('wp_ajax_image_ajax', array($this,'ajax_call'));
      add_action('wp_ajax_nopriv_image_ajax', array($this,'ajax_call'));
     
    }

    public static function instance() {
      if (!isset(self::$instance)) {
        self::$instance = new self();
        self::$instance->init();
      }
      return self::$instance;
    }
  }

  CISCF7_ajax::instance();
}

function CF7POPUP_image_uploader_field( $name, $value = '',$number) {
    $image = ' button">Upload image';
    $image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
    $display = 'none'; // display state ot the "Remove image" button
 
    if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
      $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
        $display = 'inline-block';
 
    } 
    return '<div><a href="#" class="misha_upload_image_button' . $image . '</a><input type="hidden" name="' . $name .'-'.$number.'" class="option"  id="' . $name . '" value="' . $value . '" /><input type="hidden" name="id" class="option" value="'.$number.'"><a href="#" class="misha_remove_image_button" style="display:inline-block;display:' . $display . '">Remove image</a></div>';
}
