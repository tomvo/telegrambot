<?php namespace tomvo\TelegramBot;

use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Valitron\Validator;

use tomvo\TelegramBot\Interfaces\Media;
use tomvo\TelegramBot\ResponseMessage;

use tomvo\TelegramBot\Exceptions\ValidationException;
use tomvo\TelegramBot\Exceptions\ResponseErrorException;


use GuzzleHttp\Middleware;

class Api {
 	const TELEGRAM_API_BASEURL = 'https://api.telegram.org/bot';

 	protected $debug = false;
    protected $guzzle = null;
    protected $guzzleMiddleware = null;

 	public function __construct($token)
 	{
 		$this->debug = $debug;
 		$this->guzzle = new GuzzleHttp\Client(['base_uri' => self::TELEGRAM_API_BASEURL . $token . '/']);
 	}


 	public function send($to, Media $media)
 	{
 		$response = null;

 		switch($media->getFormMethod()){
 			case \GuzzleHttp\RequestOptions::FORM_PARAMS:
 				$response = $this->guzzle->post($media->getSendEndpoint(), [
		 			'form_params' => ['chat_id' => $to] + $media->transform()
		 		]);
 			break;
 			case \GuzzleHttp\RequestOptions::MULTIPART:
 				$multipartData = [
 					[
			            'name'     => 'chat_id',
			            'contents' => (string) $to,
			        ]
 				];

				$response = $this->guzzle->post($media->getSendEndpoint(), [
		 			'multipart' => array_merge($multipartData, $media->transform())
		 		]);
 			break;
 		}


 		$response = json_decode( $response->getBody(), true);

 		if(!isset($response['ok']) || $response['ok'] != true ){
 			throw new ResponseErrorException;
 		}
 		
 		return new ResponseMessage($response['result'], $this);
 	}

 	/**
 	 * Handles an incoming update
 	 * @author Tom van Oorschot <tom@customerconnect.de>
 	 * @date   2015-07-01T08:49:41+0100
 	 * @return [type]                   [description]
 	 */
 	public function handleUpdate(Array $data)
 	{
 		if(isset($data['result'])) $data = $data['result'];
 		
 		$v = new Validator($data);
 		$v->rules([
		    'required' => 'update_id',
		    'required' => 'message'
		]);

		if(!$v->validate()) throw new ValidationException(print_r($v->errors(), true));

		$message = new ResponseMessage($data['message'], $this);

		return $message;
 	}

 	public function sendAction($action)
 	{
 		//sendAction only support these actions
 		if(!in_array($action, ['upload_photo', 'record_video', 'record_audio', 'upload_document', 'find_location'])){
 			throw new DefinitionException('Provided sendAction not supported');
 		}

 		$response = $this->guzzle->post('sendAction', [
 			'form_params' => ['chat_id' => $to, 'action' => $action]
 		]);

 		$response = json_encode($response->getBody());

 		return $response->ok;
 	}

 	public function setWebhook($url)
 	{
		$response = $this->guzzle->post('setWebhook', [
 			'form_params' => ['url' => $url]
 		]);

 		$response = json_encode($response->getBody());
 		return $response->ok;
 	}


 	public function getGuzzleClient()
 	{
 		return $this->guzzle;
 	}
 	
 
}