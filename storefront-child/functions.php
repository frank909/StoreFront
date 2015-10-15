<?php


function show_wp_options()
{
	global $wpdb;				
	$results = $wpdb->get_results( "SELECT * FROM wp_options ", OBJECT );	
	echo "<pre>".print_r($results,1)."</pre>"; 
}
//add_action('init','show_wp_options');

			
function pdf_pager_shortcode( $atts ) {

	global $post;

	extract( shortcode_atts( array( 'type' => 'attachment'), $atts ) );

	$helpmessage = '';
	$form = $count = $list = ''; 

	$limit = $_GET['limit'];
	$searchterm =  $_GET['searchterm'];
	
	if($limit < 10)
	{
		$helpmessage = " Enter a number greater than 10.";
	}
	
		
	$paged = get_query_var('paged') ? get_query_var('paged') : 1;  

	$args = array ( 
		'posts_per_page' => $limit, 
		'post_type' => $type,
		'post_status' =>  'any',
		'post_mime_type' => 'application/pdf',
		'paged' => $paged,
		's' => $searchterm
		);
		
	query_posts( $args );	
	$query2 = new WP_Query( $args );

/* 	if(isset($searchterm)){ $st_message = ' <span class="help-inline">Search for: ' . $searchterm .'</span>';  }
	if(isset($helpmessage)){ $h_message = $helpmessage; } */

	$form .= '<div class="entry-content">'
	.'<section style="margin: 20px 0;padding-bottom:8px;border-bottom: 1px solid #d1d2d4;">'
	.'<div class="row-fluid">'		
	.'<div class="span8 inline" style="display:inline;float:left;">'									
	.'<form action="' . site_url() . '/' . $post->post_name . '" method="get" id="pdfsearchForm" name="pdfsearchForm" class="form-inline">'
	.'<input type="text" name="searchterm" id="searchterm" value="' . $searchterm . '" class="inline input-medium" />'
	.'<input type="hidden" name="limit" value="'. $limit . '" />'
	.'<input type="submit" name="submit" value="Search" class="btn btn-inverse btn-small" />'								
	. $st_message
	.'</form>'										
	.'</div>'
	.'<div class="span3 offset1 inline" style="display:inline;">'
	.'<form action="' . site_url() . '/' . $post->post_name . '" method="get" id="postperpageForm" name="postperpageForm" class="form-inline">'
	.'<input type="text" id="limit" class="inline input-mini" name="limit" value="'. $limit . '" maxlength="3" />'
	.'<input type="hidden" name="searchterm" value="' . $searchterm . '" />'
	.'<input type="submit" name="submit" value="Per Page" class="btn btn-inverse btn-small" />'
	.'<span class="help-inline">'										
	. $h_message
	.'</form>'
	.'</div>'
	.'</div>'
	.'</section>'
	.'</div>'
	.'<div class="clearfix"></div>';

	if(have_posts()) {	
		/* The 2nd Query (without global var) */
		$total = '		Total ' . $query2->found_posts;	
	}
	$count .= '<div class="container">'
	.'<div class="row-fluid">'
	.'<div class="span4">'
	.'<h3>PDF Downloads ('. $total .')</h3>'
	.'</div>'
	.'</div>'
	.'</div>';

	while ( have_posts() ) { 
		the_post();  
		
		$url = wp_get_attachment_url( ID ); 
		$title = get_the_title();
		
		$list .= '<li>'
		.'<img src="http://cityofpasadena.net/WorkArea/images/ui/icons/filetypes/acrobat.png" alt="acrobat icon" />'
		.'<a href="'. $url .'" title="' . $title . '" target="_blank">'. $title .'</a>' 
		.'</li>';
	}

	return $form . $count 
	. '<div class="listings clearfix">'
	. '<ul class="unstyled">' 
	. $list 
	. '</ul>'
	//<!-- pagination here -->
	.'<div class="row-fluid">'
	.'<div class="container">'
	.'<div class="span4 offset2">'
	// For more options and info view the docs for paginate_links()
	// http://codex.wordpress.org/Function_Reference/paginate_links
	. paginate_links()
	.'</div>'
	.'</div>'
	.'</div>'				         
	.'</div>'
	. wp_reset_query(); // end return

}
add_shortcode( 'pdf_pager', 'pdf_pager_shortcode' );