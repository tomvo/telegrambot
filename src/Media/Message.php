<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Interfaces\Media;
use tomvo\TelegramBot\Traits\Respondable;

class Message extends Base implements Media {
	use Respondable;

	protected $fillable = ['message_id', 'text'];
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