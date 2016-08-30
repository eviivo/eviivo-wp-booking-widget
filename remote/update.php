<?php
/**
 * The remote host file to process update requests
 *
 */
if ( !isset( $_POST['action'] ) ) {
	echo '0';
	exit;
}

//set up the properties common to both requests
$obj = new stdClass();
$obj->slug = 'eviivo-booking-widget.php';
$obj->name = 'eviivo Booking Widget Plugin';
$obj->plugin_name = 'eviivo-booking-widget.php';
$obj->new_version = '1.0';
// the url for the plugin homepage
$obj->url = 'https://eviivo.com/';
//the download location for the plugin zip file (can be any internet host)
$obj->package = 'http://marketing.eviivo.com/plugins/eviivo-wp-booking-widget.zip';

switch ( $_POST['action'] ) {

case 'version':
	echo serialize( $obj );
	break;
case 'info':
	$obj->requires = '4.0';
	$obj->tested = '4.6';
	$obj->downloaded = 108;
	$obj->last_updated = '2016-08-25';
	$obj->sections = array(
		'description' => 'eviivo booking form generator plugin',
		//'another_section' => 'This is another section',
		//'changelog' => 'Some new features'
	);
	$obj->download_link = $obj->package;
	echo serialize($obj);
case 'license':
	echo serialize( $obj );
	break;
}

