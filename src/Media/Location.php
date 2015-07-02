<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Interfaces\Media;
use tomvo\TelegramBot\Traits\Respondable;

class Location extends Base implements Media {
	use Respondable;

	protected $fillable = [
		'latitude', 
		'longitude', 
		'reply_to_message_id', 
		'reply_markyup'
	];
	protected $required = [];

	public $messageId;
	public $chat_id;
	public $text;


	public static function fromResponse(Array $data){

	}

	public function transform()
	{
		return [
			'latitude' => $this->latitude,
			'longitude' => $this->longitude,
		];

	}

	public function getSendEndpoint()
	{
		return 'sendLocation';
	}

	public function getFormMethod()
	{
		return \GuzzleHttp\RequestOptions::FORM_PARAMS;
	}
}