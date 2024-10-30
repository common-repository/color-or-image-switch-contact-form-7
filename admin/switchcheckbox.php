<?php
/**
** A base module for [checkbox], [checkbox*], and [radio]
**/

/* form_tag handler */

add_action( 'wpcf7_init', 'wpcf7_add_form_tag_switchcheckbox', 10, 0 );
function wpcf7_add_form_tag_switchcheckbox() {
	wpcf7_add_form_tag( array( 'switchradio', 'switchradio*' ) ,'wpcf7_switchcheckbox_form_tag_handler',array(
			'name-attr' => true,
			'selectable-values' => true,
			'multiple-controls-container' => true,
		)
	);
}


function wpcf7_switchcheckbox_form_tag_handler( $tag ) {
	if ( empty( $tag->name ) ) {
		return '';
	}
	
	/*validation error*/
	$validation_error = wpcf7_get_validation_error( $tag->name );
	$class = wpcf7_form_controls_class( $tag->type );

	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
	}

	
	/*All attribute*/
	$multiple = false;

	$atts = array();
	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();

	$html = '';
	$count = 0;
	$cnt= 0;

	$values = $tag->values;
	$labels = $tag->labels;

	$type = $tag->get_option( 'type' )[0];
	$tooltip = $tag->has_option( 'tooltip' );
	$ids = $tag->get_option( 'id' );
	

	$images = array();

	/*Images array*/
	if(!empty($ids)){
		foreach ($ids as $value) {
			$image = $tag->get_option( 'image-'.$value );
			$label = $tag->get_option( 'imglabels-'.$value );
			$images[] = array($image[0],$label[0]);
		}
	}

	/*default selected*/
	$default_choice = $tag->get_default_option( null, array(
		'multiple' => $multiple,
	) );


	$hangover = wpcf7_get_hangover( $tag->name, $multiple ? array() : '' );
	
	/*for image*/
	if($type == "ciscf7_image"){
		foreach ($images as $key => $value) {
			$default = $tag->get_option( 'default' );
			if ( $cnt == 0) {
				$checked = $default;
			} else {
				$checked = 0;
			}
			$cnt++;
			$imgs = wp_get_attachment_url( $value[0] );
			$item_atts = array(
				'type' => 'radio',
				'name' => $tag->name,
				'value' => $imgs,
				'checked' => $checked ? 'checked' : '',
			);
			$item_atts = wpcf7_format_atts( $item_atts );
			if($tooltip == 1){
				$item = sprintf('<span class="ciscf7_tooltip"><input %1$s /><span class="wpcf7-list-item-label %2$s" style="background-image:url(%3$s)"><span class="tooltiptext">%4$s</span></span></span>',$item_atts, $type, $imgs, $value[1]);
			}else{
				$item = sprintf('<input %1$s /><span class="wpcf7-list-item-label %2$s" style="background-image:url(%3$s)"></span>',$item_atts, $type, $imgs);
			}
			$class = 'wpcf7-list-item '.$type;
			$count += 1;
			if ( 1 == $count ) {
				$class .= ' first';
			}
			if ( count( $values ) == $count ) { // last round
				$class .= ' last';
			}
			$item = '<span class="' . esc_attr( $class ) . '">' . $item . '</span>';
			$html .= $item;
		}
	}else{
		/*for label and color*/
		foreach ( $values as $key => $value ) {
			//print_r($value);
			if ( $hangover ) {
				$checked = in_array( $value, (array) $hangover, true );
			} else {
				$checked = in_array( $value, (array) $default_choice, true );
			}
			//print_r($checked);
			//echo "</br>";
			if ( isset( $labels[$key] ) ) {
				$label = $labels[$key];
			} else {
				$label = $value;
			}

			$item_atts = array(
				'type' => 'radio',
				'name' => $tag->name . ( $multiple ? '[]' : '' ),
				'value' => $value,
				'checked' => $checked ? 'checked' : '',
			
			);

			$item_atts = wpcf7_format_atts( $item_atts );
			$strtr = explode("--",$value);
			
			if($tooltip == 1){
				
				if($type == "ciscf7_color") {
					
					$item = sprintf('<span class="ciscf7_tooltip"><input %1$s /><samp class="wpcf7-list-item-label %2$s" style="background-color:%3$s"><span class="tooltiptext">%4$s</span></samp></span>',$item_atts, $type, $strtr[0], $strtr[1]);
				}
				if($type == "ciscf7_text") {
					$item = sprintf('<span class="ciscf7_tooltip"><input %1$s /><samp class="wpcf7-list-item-label %2$s">%3$s<span class="tooltiptext">%3$s</span></samp></span>',$item_atts, $type, $strtr[1]);
				}
				
			}else{
				if($type == "ciscf7_color") {
					
					$item = sprintf('<input %1$s /><samp class="wpcf7-list-item-label %2$s" style="background-color:%3$s"></samp>',$item_atts, $type, $strtr[0]);
				}
				if($type == "ciscf7_text") {
					$item = sprintf('<input %1$s /><samp class="wpcf7-list-item-label %2$s">%3$s</samp>',$item_atts, $type, $strtr[0]);
				}
			}

			$class = 'wpcf7-list-item '.$type;
			$count += 1;
			if ( 1 == $count ) {
				$class .= ' first';
			}
			if ( count( $values ) == $count ) { // last round
				$class .= ' last';
			}
			$item = '<span class="' . esc_attr( $class ) . '">' . $item . '</span>';
			$html .= $item;
		}
	}
	


	
	$atts = wpcf7_format_atts( $atts );
	$html = sprintf(
		'<span class="wpcf7-form-control-wrap ciscf7_mainblock %1$s"><span %2$s>%3$s</span>%4$s</span>',
		sanitize_html_class( $tag->name ), $atts, $html, $validation_error );

	return $html;
}


/* Validation filter */
add_filter( 'wpcf7_validate_switchradio','wpcf7_switchcheckbox_validation_filter', 10, 2 );
add_filter( 'wpcf7_validate_switchradio*','wpcf7_switchcheckbox_validation_filter', 10, 2 );
function wpcf7_switchcheckbox_validation_filter( $result, $tag ) {
	$name = $tag->name;
	$is_required = $tag->is_required() || 'radio' == $tag->type;
	$name = sanitize_text_field( $_POST[$name] );
	$value = isset( $name ) ? (array) $name : array();
	if ( $is_required and empty( $value ) ) {
		$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
	}
	return $result;
}





/* Tag generator */
add_action( 'wpcf7_admin_init','wpcf7_add_tag_generator_switchcheckbox_and_switchradio', 30, 0 );
function wpcf7_add_tag_generator_switchcheckbox_and_switchradio() {
	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'switchradio', __( 'switch radio buttons', 'contact-form-7' ), 'wpcf7_tag_generator_switchcheckbox' );
}

function wpcf7_tag_generator_switchcheckbox( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );
	$type = $args['id'];
	$type = 'switchradio';
	if ( 'switchradio' == $type ) {
		$description = __( "Generate a form-tag for a group of radio buttons. For more details, see %s.", 'contact-form-7' );
	}
	$desc_link = wpcf7_link( __( 'https://contactform7.com/checkboxes-radio-buttons-and-menus/', 'contact-form-7' ), __( 'Checkboxes, Radio Buttons and Menus', 'contact-form-7' ) );

	?>
		<div class="control-box">
			<fieldset>
				<legend><?php echo sprintf( esc_html( $description ), $desc_link ); ?></legend>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></th>
							<td>
								<fieldset>
								<legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></legend>
								<label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'contact-form-7' ) ); ?></label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>">
									<?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?>										
								</label>
							</th>
							<td>
								<input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" />
							</td>
						</tr>

						<tr>
		                    <th scope="row"><label><?php echo esc_html( __( 'Type', CISCF7_DOMAIN ) ); ?></label></th>
		                    <td>
		                        <input type="radio" name="type" value="ciscf7_color" class="option">Color
		                        <input type="radio" name="type" value="ciscf7_text" class="option">Text
		                        <input type="radio" name="type" value="ciscf7_image" class="option">Image
		                        
		                    </td>
	                    </tr>

						<tr id="hide_cat_boxx">
							<th scope="row"><?php echo esc_html( __( 'Options', 'contact-form-7' ) ); ?></th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<?php echo esc_html( __( 'Options', 'contact-form-7' ) ); ?>											
									</legend>
									<textarea name="values" class="code values" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>"></textarea>
									<br />
									<label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>">
										<span class="description">
											<?php echo esc_html( __( "One option per line.", 'contact-form-7' ) ); ?>
										</span>
									</label>
									<br />
									<label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>">
										<span class="description">
											<?php echo esc_html( __( 'Give Value--label pair', 'contact-form-7' ) ); ?>	
										</span>
									</label>
									<br />
									<label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>">
										<span class="description">
											<?php echo esc_html( __( '(example: for color == "#75ab00--10", for text == "xxl--xxl" )', 'contact-form-7' ) ); ?>
										</span>
									</label>
									<br />
								</fieldset>
							</td>
						</tr>
						
						<tr id="hide_img_box" style="display: none;">
							<th></th>
							<td>
								<table class="form-table">
									
					                    <tr class="last_add_more">
					                    	<td>
					                    		<button class="add_more_img">Add More</button>
					                    	</td>
					                    </tr>
				                    
			                    </table>
			                </td>
	                    </tr>

						<tr>
		                    <th scope="row"><label><?php echo esc_html( __( 'Show ToolTip', CISCF7_DOMAIN ) ); ?></label></th>
		                    <td>
		                        <input type="checkbox" name="tooltip" value="yes" class="option oc_tooltip_cls">Show Tooltip       
		                    </td>
	                    </tr>

	                    <tr>
		                    <th scope="row"><label><?php echo esc_html( __( 'Select Default', CISCF7_DOMAIN ) ); ?></label></th>
		                    <td>
		                        <input type="checkbox" name="default:1" value="1" class=" option">Select Default Option       
		                    </td>
	                    </tr>

						<tr>
							<th scope="row">
								<label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>">
									<?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" />
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" />
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</div>
		<div class="insert-box">
			<input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />
			<div class="submitbox">
				<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
			</div>
			<br class="clear" />
			<p class="description mail-tag">
				<label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?>
					<input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" />
				</label>
			</p>
		</div>
	<?php
}









