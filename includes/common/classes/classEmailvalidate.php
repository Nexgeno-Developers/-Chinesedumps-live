<?PHP

	error_reporting(0);
	require_once __DIR__ . '/../../config/config.php';

	class classEmailvalidate
	{
	    
	    function email_validate_api($email){
	        
	        $curl = curl_init();

            $apiKey = secret('BOUNCIFY_API_KEY', '');
            if ($apiKey === '') {
                return json_encode(array('error' => 'Email validation API not configured'));
            }

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.bouncify.io/v1/verify?timeout=180000&apikey=' . urlencode($apiKey) . '&email=' . urlencode($email),
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'accept: application/json'
              ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            return $response;
	    }

	    
	}
?>
