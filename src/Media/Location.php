<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Interfaces\Media;

class Location extends Base implements Media {
	protected $fillable = [
		'latitude', 
		'longitude'
	];
	protected $required = [];

	public $messageId;
	public $chat_id;
	public $text;

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