<?php

class bt_bb_video extends BT_BB_Element {

	function handle_shortcode( $atts, $content ) {
		extract( shortcode_atts( apply_filters( 'bt_bb_extract_atts_' . $this->shortcode, array(
			'video'            	=> '',
			'disable_controls' 	=> '',
			'loop_video'		=> '',
			'autoplay_video'	=> ''
		) ), $atts, $this->shortcode ) );
		
		$class = array( $this->shortcode );
		
		if ( $el_class != '' ) {
			$class[] = $el_class;
		}
		
		$id_attr = '';
		if ( $el_id != '' ) {
			$id_attr = ' ' . 'id="' . esc_attr( $el_id ) . '"';
		}

		$style_attr = '';
		$el_style = apply_filters( $this->shortcode . '_style', $el_style, $atts );
		if ( $el_style != '' ) {
			$style_attr = ' ' . 'style="' . esc_attr( $el_style ) . '"';
		}
		
		if ( $disable_controls != '' ) {
			$class[] = $this->prefix . 'disable_controls' . '_' . $disable_controls;
		}

		if ( $autoplay_video != '' ) {
			$class[] = 'animate bt_bb_video_autoplay';
		}

		do_action( $this->shortcode . '_before_extra_responsive_param' );
		foreach ( $this->extra_responsive_data_override_param as $p ) {
			if ( ! is_array( $atts ) || ! array_key_exists( $p, $atts ) ) continue;
			$this->responsive_data_override_class(
				$class, $data_override_class,
				apply_filters( $this->shortcode . '_responsive_data_override', array(
					'prefix' => $this->prefix,
					'param' => $p,
					'value' => $atts[ $p ],
				) )
			);
		}

		$class = apply_filters( $this->shortcode . '_class', $class, $atts );

		$attr = array( 
			'src' 		=> $video,
			'loop' 		=> $loop_video
		);

		$output = '[video src="' . $video . '" loop="' . $loop_video . '"]';
		
		
		$output = '<div' . $id_attr . ' class="' . implode( ' ', $class ) . '"' . $style_attr . '>' . do_shortcode( $output ) . '</div>';
		
		$output = apply_filters( 'bt_bb_general_output', $output, $atts );
		$output = apply_filters( $this->shortcode . '_output', $output, $atts );
		
		return $output;

	}

	function map_shortcode() {
		bt_bb_map( $this->shortcode, array( 'name' => esc_html__( 'Video', 'bold-builder' ), 'description' => esc_html__( 'Video player', 'bold-builder' ), 'icon' => $this->prefix_backend . 'icon' . '_' . $this->shortcode,
			'params' => array(
				array( 'param_name' => 'video', 'type' => 'textfield', 'heading' => esc_html__( 'Video', 'bold-builder' ) ),
				array( 'param_name' => 'disable_controls', 'type' => 'dropdown', 'heading' => esc_html__( 'Disable player controls', 'bold-builder' ), 'description' => esc_html__( 'Useful when embedded video has its own controls, e.g. Vimeo', 'bold-builder' ),
					'value' => array(
						esc_html__( 'No', 'bold-builder' ) 		=> 'no',
						esc_html__( 'Yes', 'bold-builder' ) 	=> 'yes'
					),
				),
				array( 'param_name' => 'loop_video', 'type' => 'dropdown', 'heading' => esc_html__( 'Enable loop video', 'bold-builder' ),
					'value' => array(
						esc_html__( 'No', 'bold-builder' ) 		=> '',
						esc_html__( 'Yes', 'bold-builder' ) 	=> 'on'
					),
				),
				array( 'param_name' => 'autoplay_video', 'type' => 'dropdown', 'heading' => esc_html__( 'Enable autoplay video', 'bold-builder' ),
					'value' => array(
						esc_html__( 'No', 'bold-builder' ) 		=> '',
						esc_html__( 'Yes', 'bold-builder' ) 	=> 'autoplay'
					),
				),
			)
		) );
	}
}