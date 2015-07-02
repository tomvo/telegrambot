<?php

use tomvo\TelegramBot\Media\Message;
use tomvo\TelegramBot\Media\Photo;

$base = realpath(__DIR__ . '/../../vendor');
require_once($base . '/autoload.php');

date_default_timezone_set('Europe/Amsterdam');

$api = new tomvo\TelegramBot\Api('[YOUR KEY]', true);

$rawData = file_get_contents('php://input');
$json = json_decode($rawData, true);

$response = $api->handleUpdate($json);

if($response->getMediaType() == 'message'){
	$response->respond(
		new Message([
			'text' => 'You sent me a text!'
		])
	);
}elseif($response->getMediaType() == 'photo'){
	$response->respond(
		new Message([
			'text' => 'You sent me a photo!'
		])
	);
}