<?php
/**
 * Mas opciones para los widgets, estilo css y formato de muestra.
 *
 * @link http://wordpress.stackexchange.com/questions/134539/how-to-add-custom-fields-to-settings-in-widget-options-for-all-registered-widget
 * @link https://github.com/lowhow/Whitecoat/blob/master/whitecoat2/functions-theme.php
 *
 * @package ekiline
 */

/**
 * 1) Extender opciones de widget, añadir CSS por cada item. Extend widget options, add CSS.
 *
 * @param string $input field name.
 * @param string $return value.
 * @param string $instance midget.
 */
function ekiline_in_widget_form( $input, $return, $instance ) {
	$instance = wp_parse_args(
		(array) $instance,
		array(
			'title'     => '',
			'text'      => '',
			'css_style' => '',
		)
	);
	if ( ! isset( $instance['css_style'] ) ) {
		$instance['css_style'] = null;
	}
	?>
	<p>
		<label for="<?php echo esc_attr( $input->get_field_id( 'css_style' ) ); ?>">
			<?php esc_html_e( 'CSS custom class (Ekiline)', 'ekiline' ); ?>
		</label>
		<input
			class="widefat" type="text"
			id="<?php echo esc_attr( $input->get_field_id( 'css_style' ) ); ?>"
			name="<?php echo esc_attr( $input->get_field_name( 'css_style' ) ); ?>"
			value="<?php echo esc_attr( $instance['css_style'] ); ?>" />
	</p>
	<?php
	$retrun = null;
	return array( $input, $return, $instance );
}

/**
 * Update widget CSS.
 *
 * @param string $instance .
 * @param string $new_instance .
 * @param string $old_instance .
 */
function ekiline_in_widget_form_update( $instance, $new_instance, $old_instance ) {
	$instance['css_style'] = wp_strip_all_tags( $new_instance['css_style'] );
	return $instance;
}

/**
 * Widget params widget CSS.
 *
 * @param string $params .
 */
function ekiline_dynamic_sidebar_params( $params ) {
	global $wp_registered_widgets;
	$widget_id  = $params[0]['widget_id'];
	$widget_obj = $wp_registered_widgets[ $widget_id ];
	$widget_opt = get_option( $widget_obj['callback'][0]->option_name );
	$widget_num = $widget_obj['params'][0]['number'];

	if ( isset( $widget_opt[ $widget_num ]['css_style'] ) ) {
		if ( ! $widget_opt[ $widget_num ]['css_style'] ) {
			$css_style = '';
		} else {
			$css_style = $widget_opt[ $widget_num ]['css_style'] . ' ';
		}
	} else {
		$css_style = '';
	}

	$params[0]['before_widget'] = preg_replace( '/class="/', 'class="' . $css_style, $params[0]['before_widget'], 1 );

	return $params;
}
// Agregar el input: prioridad 5 y 3 parametros.
add_action( 'in_widget_form', 'ekiline_in_widget_form', 5, 3 );
// Callback para actualización de parametros.
add_filter( 'widget_update_callback', 'ekiline_in_widget_form_update', 5, 3 );
// Agregar los CSS con reemplazo.
add_filter( 'dynamic_sidebar_params', 'ekiline_dynamic_sidebar_params' );


/**
 * 2) Extender opciones de widget, añadir nueva presentacion por cada item. Extend widget options, add view/format options.
 *
 * @link http://themefoundation.com/custom-widget-options/
 * @link https://wordpress.stackexchange.com/questions/134539/how-to-add-custom-fields-to-settings-in-widget-options-for-all-registered-widget
 * @link https://stackoverflow.com/questions/41113890/retrieve-value-widget-form-option-wordpress
 *
 * @param string $widget .
 * @param string $return .
 * @param string $instance midget .
 */
function ekiline_widget_view( $widget, $return, $instance ) {

	$instance = wp_parse_args( (array) $instance, array( 'viewFormat' => 'none' ) );
	if ( ! isset( $instance['viewFormat'] ) ) {
		$instance['viewFormat'] = null;
	}
	?>
	<p>
		<label for="<?php echo esc_attr( $widget->get_field_id( 'viewFormat' ) ); ?>"><?php esc_html_e( 'Format (Ekiline)', 'ekiline' ); ?></label>

		<select id="<?php echo esc_attr( $widget->get_field_id( 'viewFormat' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'viewFormat' ) ); ?>">
			<option <?php selected( $instance['viewFormat'], 'none' ); ?> value="none"><?php esc_html_e( 'Default', 'ekiline' ); ?></option>
			<option <?php selected( $instance['viewFormat'], 'dropdown' ); ?>value="dropdown"><?php esc_html_e( 'Dropdown', 'ekiline' ); ?></option>
			<option <?php selected( $instance['viewFormat'], 'modal' ); ?> value="modal"><?php esc_html_e( 'Modal', 'ekiline' ); ?></option>
		</select>
	</p>
	<?php
}
add_action( 'in_widget_form', 'ekiline_widget_view', 5, 3 );

/**
 * Update widget format.
 *
 * @param string $instance .
 * @param string $new_instance .
 * @param string $old_instance .
 */
function ekiline_widget_view_save( $instance, $new_instance, $old_instance ) {

	$instance['viewFormat'] = $new_instance['viewFormat'];
	return $instance;

}
add_filter( 'widget_update_callback', 'ekiline_widget_view_save', 5, 3 );

/**
 * Widget params format.
 *
 * @param string $params .
 */
function ekiline_widget_show( $params ) {

	global $wp_registered_widgets;
	$widget_id  = $params[0]['widget_id'];
	$widget_obj = $wp_registered_widgets[ $widget_id ];
	$widget_opt = get_option( $widget_obj['callback'][0]->option_name );
	$widget_num = $widget_obj['params'][0]['number'];

	// Variables para modificar la estructura del widget.
	$view_format = '';

	$bef_wdg = $params[0]['before_widget'];
	$bef_ttl = $params[0]['before_title'];
	$aft_ttl = $params[0]['after_title'];
	$aft_wdg = $params[0]['after_widget'];

	$widget_ttl = '';

	if ( isset( $widget_opt[ $widget_num ]['title'] ) ) {
		if ( '' !== $widget_opt[ $widget_num ]['title'] ) {
			$widget_ttl = $widget_opt[ $widget_num ]['title'];
		}
	}

	if ( isset( $widget_opt[ $widget_num ]['viewFormat'] ) ) {

		$view_format = $widget_opt[ $widget_num ]['viewFormat'];

		if ( 'dropdown' === $view_format ) {

			$widget_ttl = ( '' !== $widget_ttl ) ? '<p class="h5" class="dropdown-header">' . $widget_ttl . '</p>' : '';

			$bef_wdg = preg_replace( '/class="/', 'class="' . $view_format . ' ', $params[0]['before_widget'], 1 );
			$bef_ttl = '<button class="btn btn-secondary btn-block dropdown-toggle" type="button" data-bs-toggle="dropdown">';
			$aft_ttl = '</button><div class="dropdown-menu">' . $widget_ttl;
			$aft_wdg = '</div>' . $aft_wdg;

		} elseif ( 'modal' === $view_format ) {

			$widget_ttl = ( '' !== $widget_ttl ) ? '<p class="h5">' . $widget_ttl . '</p>' : '';

			$bef_wdg  = $bef_wdg;
			$bef_ttl  = '<button class="btn btn-primary btn-block" type="button" data-bs-toggle="modal" data-bs-target="#wdgModal-' . $widget_id . '">';
			$aft_ttl  = '</button><div class="modal fade" id="wdgModal-' . $widget_id . '">';
			$aft_ttl .= '<div class="modal-dialog modal-dialog-centered"><div class="modal-content">';
			$aft_ttl .= '<div class="modal-header">' . $widget_ttl . '<button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">';
			$aft_wdg  = '</div></div></div></div>' . $aft_wdg;
		}
	}
	// Agrega etiquetas.
	$params[0]['before_widget'] = $bef_wdg;
	$params[0]['before_title']  = $bef_ttl;
	$params[0]['after_title']   = $aft_ttl;
	$params[0]['after_widget']  = $aft_wdg;

	return $params;

}
add_filter( 'dynamic_sidebar_params', 'ekiline_widget_show' );
