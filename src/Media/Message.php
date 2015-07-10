<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Interfaces\Media;

class Message extends Base implements Media {
	protected $fillable = ['text'];
	protected $required = [];

	public function transform()
	{
		return [
	 		'text' => $this->text
		];
	}

	public function getSendEndpoint()
	{
		return 'sendMessage';
	}

	public function getFormMethod()
	{
		return \GuzzleHttp\RequestOptions::FORM_PARAMS;
	}
}