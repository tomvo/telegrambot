<?php namespace tomvo\TelegramBot\Traits;

use tomvo\TelegramBot\Exceptions\DefinitionException;

trait Uploadable {
	protected $transformKey = null;
	protected $file;
	protected $file_id;

	public function setTransformKey($key)
	{
		$this->transformKey = $key;
	}

	public function transform()
	{
		if(empty($this->file) && empty($this->file_id)) throw new ValidationException('Either file or file_id need to be provided');
		if(isset($this->file)){
			if(!$this->isFile($this->file)) throw new ValidationException('Provided file is not found in the filesystem');
			
			$this->isFile = true;
		}

		if(!isset($this->transformKey)) throw new DefinitionException('transformKey needs to be set when using the uploadable trait');


		//The API will either send the request as a simple urlencoded form post or as a multipart request. The multipart is for uploading data.
		//The body is built here and then passed on to guzzle in the API class.
		$data = [];
		switch($this->getFormMethod()){
 			case \GuzzleHttp\RequestOptions::FORM_PARAMS:
 				$data = [
			 		$this->transformKey => $this->file_id
			 	];

			 	if(isset($this->reply_to_message_id)) $data['reply_to_message_id'] = $this->reply_to_message_id;
			 	if(isset($this->reply_markup)) $data['reply_markup'] = $this->reply_markup;
 			break;
 			case \GuzzleHttp\RequestOptions::MULTIPART:
 				$data = [
			 		[
			 			'name' => $this->transformKey,
			 			'contents' => fopen($this->file, 'r')
			 		]
			 	];

			 	if(isset($this->reply_to_message_id)) {
			 		array_push($data, [
			 			'name' => 'reply_to_message_id',
			 			'contents' => $this->reply_to_message_id,
			 		]);
			 	}

			 	if(isset($this->reply_markup)) {
			 		array_push($data,[
			 			'name' => 'reply_markup',
			 			'contents' => $this->reply_markup
			 		]);
			 	}

 			break;
 		}

 		return $data;

	}
}