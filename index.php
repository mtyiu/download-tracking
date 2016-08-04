<?php
function getServerParams() {
	$path = $_GET[ 'p' ];
	$ip = $_SERVER[ 'REMOTE_ADDR' ];
	$referer = $_SERVER[ 'HTTP_REFERER' ];
	$useragent = $_SERVER[ 'HTTP_USER_AGENT' ];

	return sprintf(
		"%-18s\t%-15s\t%-20s\t%-20s\t%s\n",
		date( 'Y-m-d H:i:s' ),
		$ip,
		substr( $path, 3 ),
		$referer ? $referer : '-',
		$useragent
	);
}

function generateEmail() {
	$path = $_GET[ 'p' ];
	$ip = $_SERVER[ 'REMOTE_ADDR' ];
	$referer = $_SERVER[ 'HTTP_REFERER' ];
	$useragent = $_SERVER[ 'HTTP_USER_AGENT' ];

	return sprintf(
		"Date:\n%s\n\nIP Address:\n%s\n\nFile:\n%s\n\nReferer:\n%s\n\nUser Agent:\n%s\n",
		date( 'Y-m-d H:i:s' ),
		$ip,
		substr( $path, 3 ),
		$referer ? $referer : '-',
		$useragent
	);
}

///////////////////////////////////////////////////////////////////////////////

$path = $_GET[ 'p' ];
$trace = true;

// IP filters
$ip_filters = array(
	'127.0.0.1',
);
foreach( $ip_filters as $ip_filter ) {
	$ip = $_SERVER[ 'REMOTE_ADDR' ];
	if ( $ip_filter == $ip )
		$trace = false;
}

if ( $trace ) {
	$log_file = 'log.txt';
	$data = getServerParams();

	if ( ! file_exists( $file ) ) {
		$data = sprintf(
			"%-18s\t%-15s\t%-20s\t%-20s\t%s\n",
			'Timestamp',
			'IP Address',
			'File',
			'Referer',
			'User Agent'
		) . "------------------\t---------------\t--------------------\t--------------------\t--------------------\n"
		  . $data;
	}

	$ret = file_put_contents( $log_file, $data, FILE_APPEND | LOCK_EX );

	// mail( 'YOUR_EMAIL', 'Notification from download', generateEmail() );
}

header( 'Content-type: application/pdf' );
// header( 'Content-Disposition: attachment; filename=' . $filename ); // or whatever the file name is
readfile( $path );
?>
