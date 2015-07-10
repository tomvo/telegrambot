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
use GuzzleHttp\HandlerStack;

class Api {
 	const TELEGRAM_API_BASEURL = 'https://api.telegram.org/bot';

 	protected $debug = false;
    protected $guzzle = null;
    protected $guzzleMiddleware = null;

 	public function __construct($token = null, HandlerStack $handler = null)
 	{
 		if(!empty($handler)){
			$this->guzzle = new GuzzleHttp\Client([
	 			'handler' => $handler
	 		]);
 		}else{
 			if(empty($token)) throw new ValidationException('No token provided');

 			$this->guzzle = new GuzzleHttp\Client([
	 			'base_uri' => self::TELEGRAM_API_BASEURL . $token . '/'
	 		]);
 		}
 	}

 	/**
 	 * Send an actual media object. The send method accepts all type of media objects can implement the media interface
 	 * 
 	 * @author Tom van Oorschot <tom@customerconnect.de>
 	 * @date   2015-07-10T08:50:45+0100
 	 * @param  [type]                   $to    [description]
 	 * @param  Media                    $media [description]
 	 * @return [type]                          [description]
 	 */
 	public function send($to, Media $media)
 	{
 		$response = null;

		$clientHandler = $this->guzzle->getConfig('handler');
		// Create a middleware that echoes parts of the request.
		$tapMiddleware = Middleware::tap(function ($request) {
		    // application/json
		    echo $request->getBody();
		    // {"foo":"bar"}
		});


 		switch($media->getFormMethod()){
 			case \GuzzleHttp\RequestOptions::FORM_PARAMS:
 				$response = $this->guzzle->post($media->getSendEndpoint(), [
		 			'form_params' => ['chat_id' => $to] + $media->transform(),
		 			'handler' => $tapMiddleware($clientHandler)
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

 	/**
 	 * Send a pending action to the client, this shows the user a 'waiting' message
 	 * Can be used while uploading large files
 	 * 
 	 * @author Tom van Oorschot <tom@customerconnect.de>
 	 * @date   2015-07-10T08:50:07+0100
 	 * @param  [type]                   $action [description]
 	 * @return [type]                           [description]
 	 */
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

 	/**
 	 * Set a webhook for the API
 	 * @author Tom van Oorschot <tom@customerconnect.de>
 	 * @date   2015-07-10T08:50:34+0100
 	 * @param  [type]                   $url [description]
 	 */
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