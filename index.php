<?php
define('CONTROLLERS',dirname(__FILE__) .'/engine/controllers/');
define('DB',dirname(__FILE__) .'/engine/database/');
define('SETTINGS',dirname(__FILE__) .'/engine/');
require_once DB.'rb.php';
require_once CONTROLLERS.'request.php';
require_once CONTROLLERS.'message.php';
require_once CONTROLLERS.'config.php';
$config = new Config();
R::setup( 'mysql:host='.$config->db_host.';dbname='.$config->db_name.'', $config->db_user, $config->db_password );
define('BOT_TOKEN', $config->token);
define('API_URL', $config->api_url.BOT_TOKEN.'/');
define('WEBHOOK_URL', $config->webhook_url);

$request = new Request();

$content = file_get_contents("php://input");
$update = json_decode($content, true);
if (!$update) {
  // receive wrong update, must not happen
	exit;
}

if (isset($update["message"])) {
	//Log your JSON requests
	$myFile = "json.txt";
	file_put_contents($myFile,$content);

	$message = new Message($update["message"]);
	$request->getChatId($message->getChatId());


	switch($message->getMessage())
	{
		case '/start':
			$request->sendMessage('Hello');
		break;


		default:$request->sendMessage('Again');
	}
}
