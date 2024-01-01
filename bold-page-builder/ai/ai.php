<?php

/**
 * Get AI response
 */

class BT_BB_AI {
	public static $_content_system_prompt;
}

BT_BB_AI::$_content_system_prompt = 'You are a copywriter and your goal is to help users generate website content based on the user prompt.';

function bt_bb_ai() {
	check_ajax_referer( 'bt_bb_nonce', 'nonce' );
	
	$keywords = wp_kses_post( $_POST['keywords'] );
	$tone = wp_kses_post( $_POST['tone'] );
	$language = wp_kses_post( $_POST['language'] );
	$target = json_decode( stripslashes( wp_kses_post( $_POST['target'] ) ), true );
	
	if ( $target == '_content' ) {
		$system_prompt = BT_BB_AI::$_content_system_prompt;
	} else {
		$system_prompt = wp_kses_post( $_POST['system_prompt'] );
	}

	$length = json_decode( stripslashes( wp_kses_post( $_POST['length'] ) ), true );
	
	$options = get_option( 'bt_bb_settings' );
	if ( is_array( $options ) ) {
		$api_key = isset( $options['openai_api_key'] ) ? $options['openai_api_key'] : '';
		$api_model = ( isset( $options['openai_model'] ) && $options['openai_model'] != '' ) ? $options['openai_model'] : 'gpt-4';
	}
	
	$url = 'https://api.openai.com/v1/chat/completions';
	
	if ( is_array( $target ) ) { // not _content
		$properties = array();
		
		foreach( $target as $k => $v ) {
			$properties[ $k ] = array( 'type' => 'string', 'description' => $v['alias'] );
		}

		$schema = array(
			'type' => 'object',
			'properties' => $properties
		);
	}
	
	$tone_prompt = '';
	if ( $tone != '' ) {
		$tone_prompt = ' The tone of the content must be ' . $tone . '.';
	}
	
	$language_prompt = '';
	if ( $language != '' ) {
		$language_prompt = ' Content must be in ' . $language . '.';
	} else {
		$language_prompt = ' Content must be in English.';
	}
	
	$length_prompt = '';
	
	if ( is_array( $target ) ) {
		$i = 0;
		foreach( $target as $k => $v ) {
			if ( $length[ $i ] != '' && abs( intval( $length[ $i ] ) ) > 0 ) {
				$w_or_c = 'words.';
				if ( str_contains( $length[ $i ], 'c' ) ) {
					$w_or_c = 'characters.';
				}
				$length_prompt .= ' ' . ucfirst( $v[ 'alias' ] ) . ' ' . 'must have' . ' ' . abs( intval( $length[ $i ] ) ) . ' ' . $w_or_c;
			}
			$i++;
		}
	} else { // _content
		if ( $length[ 0 ] != '' && abs( intval( $length[ 0 ] ) ) > 0 ) {
			$w_or_c = 'words.';
			if ( str_contains( $length[ 0 ], 'c' ) ) {
				$w_or_c = 'characters.';
			}
			$length_prompt .= ' Content must have' . ' ' . abs( intval( $length[ 0 ] ) ) . ' ' . $w_or_c;
		}
	}

	if ( is_array( $target ) ) {
		$data = array(
			'model' => $api_model,
			'messages' => [array(
				'role' => 'user',
				'content' => $keywords
			),array(
				'role' => 'system',
				'content' => $system_prompt . $tone_prompt . $length_prompt . $language_prompt
			)],
			'functions' => [array(
				'name' => 'get_content',
				'parameters' => $schema
			)],
			'function_call' => array( 'name' => 'get_content' )
		);
	} else { // _content
		$data = array(
			'model' => $api_model,
			'messages' => [array(
				'role' => 'user',
				'content' => $keywords
			),array(
				'role' => 'system',
				'content' => $system_prompt . $tone_prompt . $length_prompt . $language_prompt
			)]
		);
	}

	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer ' . $api_key
	));

	$response = curl_exec( $ch );
	curl_close( $ch );

	$result = json_decode( $response, true );
	
	if ( $result ) {
		if ( is_array( $result ) ) {
			if ( isset( $result['error'] ) ) {
				echo $result['error']['message'];
			} else {
				if ( is_array( $target ) ) {
					echo json_encode( json_decode( $result['choices'][0]['message']['function_call']['arguments']) );
				} else { // _content
					echo json_encode( array( '_content' => trim( $result['choices'][0]['message']['content'], '"' ) ) );
				}
			}
		}
	}
	
	wp_die();
}
add_action( 'wp_ajax_bt_bb_ai', 'bt_bb_ai' );