<?php
  /**
   * Post file contents through cURL
   */
  function file_post_contents_curl($url) {
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json'
    ) );

    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
    $data = curl_exec( $ch );

    if( curl_errno( $ch ) ) {
      $output = array( 'success' => false, 'data' => curl_error($ch) );
      return $output;
    } else {
      $output = array( 'success' => true, 'data' => $data );
      return $output;
    }

    curl_close( $ch );
  }
?>
