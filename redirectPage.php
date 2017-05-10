<?php
	ob_start();
    		session_start();

		require_once('Facebook/autoload.php');
		$fb = new Facebook\Facebook([
		  'app_id' => 'App ID',
		  'app_secret' => 'Secret ID',
		  'default_graph_version' => 'v2.2',
		]);

		if(isset($_GET['state']))
		{
		      if($_SESSION['FBRLH_' . 'state'])
		      {
		          $_SESSION['FBRLH_' . 'state'] = $_GET['state'];
		      }
		}
		 
		$helper = $fb->getRedirectLoginHelper();

		 
		try {
		  $accessToken = $helper->getAccessToken();
		  //echo 'test...'; exit;
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}

		 
		if (! isset($accessToken)) {

		  if ($helper->getError()) {
		    header('HTTP/1.0 401 Unauthorized');
		    echo "Error: " . $helper->getError() . "\n";
		    echo "Error Code: " . $helper->getErrorCode() . "\n";
		    echo "Error Reason: " . $helper->getErrorReason() . "\n";
		    echo "Error Description: " . $helper->getErrorDescription() . "\n";
		  } else {
		    header('HTTP/1.0 400 Bad Request');
		    echo 'Bad request';
		  }
		  exit;
		}
		 
		// Logged in
		// The OAuth 2.0 client handler helps us manage access tokens
		$url = "https://graph.facebook.com/v2.2/me?fields=id,name,gender,email,picture,cover,birthday&access_token={$accessToken}";
		$headers = array("Content-type: application/json");
		//echo 'login success...'; exit;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$st = curl_exec($ch);
		$result = json_decode($st, TRUE);

		$oAuth2Client = $fb->getOAuth2Client();
		 
		// Get the access token metadata from /debug_token
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);
		 
		// Get user’s Facebook ID
		$userId = $tokenMetadata->getField('user_id');


			try {
				  // Returns a `Facebook\FacebookResponse` object
				if(isset($_SESSION['facebook_access_token']))
				{
					$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);

				}
				else
				{
					$_SESSION['facebook_access_token'] = (string) $accessToken;
					$oAuth2Client = $fb->getOAuth2Client();
					$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
					$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
					$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
				}

				  $response = $fb->get('/me?fields=id,name', $accessToken);
				} catch(Facebook\Exceptions\FacebookResponseException $e) {
				  echo 'Graph returned an error: ' . $e->getMessage();
				  exit;
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
				  echo 'Facebook SDK returned an error: ' . $e->getMessage();
				  exit;
				}
				
				var_dump($result); exit; //Now Facebook public data is available here. now you can make a login section
				
?>
