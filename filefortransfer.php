<?php
require_once __DIR__ . '/includes/config/load_secrets.php';

/* Source File Name and Path */
$remote_file = '/public_html/backupafter-infected-now-migration-to-new-server.zip';
 
/* FTP Account */
$ftp_host = secret('FTP_HOST', ''); /* host */
$ftp_user_name = secret('FTP_USER', ''); /* username */
$ftp_user_pass = secret('FTP_PASS', ''); /* password */
 
 
/* New file name and path for this file */
$local_file = 'files.zip';
 
/* Connect using basic FTP */
$connect_it = ftp_connect( $ftp_host );
 
/* Login to FTP */
$login_result = ftp_login( $connect_it, $ftp_user_name, $ftp_user_pass );
 
/* Download $remote_file and save to $local_file */
if ( ftp_get( $connect_it, $local_file, $remote_file, FTP_BINARY ) ) {
    echo "WOOT! Successfully written to $local_file\n";
}
else {
    echo "Doh! There was a problem\n";
}
 
/* Close the connection */
ftp_close( $connect_it );
