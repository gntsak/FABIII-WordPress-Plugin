<?php
/*
Plugin Name: FAB3 Bailiffs Endpoint
Plugin URI: https://it.auth.gr
Description: Used to return the list of all local Bailiffs in a custom endpoint
Version: 1.0.0
Author: Georgios Nikolaos Tsakonas
License: GPLv2 or later
Text Domain: fab3-bailiffs
*/

/*
 * Function that registers the custom endpoint getBailiffs/v2/getAll
 */
 
// GET resource 

add_action( 'rest_api_init', 'fab3_bailiffs_endpoint_register_route' );
function fab3_bailiffs_endpoint_register_route() {
    register_rest_route( 'getBailiffs/v2', '/getAll', array(
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => 'fab3_get_bailiffs_list',
                )
            );
}

// POST resource 

add_action( 'rest_api_init', 'fab3_bailiffs_postalcode_endpoint_register_route' );
function fab3_bailiffs_postalcode_endpoint_register_route() {
    register_rest_route( 'getBailiffs/v2', '/getAllFiltered', array(
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => 'fab3_get_bailiffs_postalcode_list',
                )
            );
}

/*
 * Function that creates the json schema for the custom endpoint
 */
 
function fab3_get_bailiffs_list() {
	/*
	 * All the bailiffs are returned
	 */
	$args = array(
		'posts_per_page' 	=> -1,
		'post_type'		 	=> 'kohtutaiturids',
		'orderby'		 	=> 'ID',
		'order'			 	=> 'ASC',
		'suppress_filters'  => false
	);
	// The bailiffs data are returned based on the previews array
	$items = get_posts( $args );
	
	/*
	 * The json schema is cretaed for the custom endpoint
	 * It includes the following info for every bailiff:
	 * - ID
	 * - Country
	 * - Details: name, lang, address, postalCode, municipality, tel
	 */
	$array_new = '';
	$array_body = array();
	foreach($items as $item){
		$array_body[] = array(
			"id"	  => $item->ID,
			"country" => 'EE',
			"details" => array(
				array(
					"name" 			=> $item->post_title,
					"lang" 			=> get_field('languages_spoken', $item->ID),
					"address" 		=> get_field('koh_adress', $item->ID),
					"postalCode" 	=> get_field('postal_code', $item->ID),
					"municipality"  => get_field('koh_linn', $item->ID),
					"tel"			=> get_field('koh_phone', $item->ID),
				)
			)
		);
	}
	$array_new = array(
		"state" => "answered",
		"competentBodies" => $array_body
	);

	echo json_encode($array_new);
}


/*
 * Function that create the json schema for the custom endpoint that 
 * will display bailiffs based on their postal code
 */
function fab3_get_bailiffs_postalcode_list() {
	/*
	 * If the filter postalCode is not present in the URL,
	 * all the bailiffs will be returned. Otherwise we get 
	 * only the bailiffs who have the specific postalCode 
	 * in their details
	 */
	if ( isset($_REQUEST['postalCode']) ) {
		$args = array(
			'posts_per_page' 	=> -1,
			'post_type'		 	=> 'kohtutaiturids',
			'orderby'		 	=> 'ID',
			'order'			 	=> 'ASC',
			'meta_key'		 	=> 'postal_code',
			'meta_value'	 	=> $_REQUEST['postalCode'],
			'suppress_filters'  => false
		);
	}
	// The bailiffs data are returned based on the previews array
	$items = get_posts( $args );
	
	/*
	 * The json schema is cretaed for the custom endpoint
	 * It includes the following info for every bailiff:
	 * - ID
	 * - Country
	 * - Details: name, lang, address, postalCode, municipality, tel
	 */
	$array_new = '';
	$array_body = array();
	foreach($items as $item){
		$array_body[] = array(
			"id"	  => $item->ID,
			"country" => 'EE',
			"details" => array(
				array(
					"name" 			=> $item->post_title,
					"lang" 			=> get_field('languages_spoken', $item->ID),
					"address" 		=> get_field('koh_adress', $item->ID),
					"postalCode" 	=> get_field('postal_code', $item->ID),
					"municipality"  => get_field('koh_linn', $item->ID),
					"tel"			=> get_field('koh_phone', $item->ID),
				)
			)
		);
	}
	$array_new = array(
		"state" => "answered",
		"competentBodies" => $array_body
	);

	echo json_encode($array_new);
}

?>