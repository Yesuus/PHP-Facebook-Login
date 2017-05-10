<?php

 session_start();


include 'Facebook/autoload.php';

$fb = new Facebook\Facebook([

  'app_id' => 'App ID',

  'app_secret' => 'Secret ID',

  'default_graph_version' => 'v2.2',

  'persistent_data_handler'=>'session',

]);

 $helper = $fb->getRedirectLoginHelper();

 $fbloginurl = 'http://localhost:81/facebookLogin/redirectPage.php';

//$permissions = []; //optional

$permissions = ['public_profile','email'];

$loginUrl = $helper->getLoginUrl($fbloginurl,$permissions);

//$loginUrl = $helper->getLoginUrl($fbloginurl, array $scope = [], string $separator = '&');

//print_r($loginUrl); exit;



?>

<!DOCTYPE html>

<html lang="en">

<body>

	<a  href="<?php echo  htmlspecialchars($loginUrl); ?>" tabindex="5">Facebook Login</a>

												
</body>

</html>