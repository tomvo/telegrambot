<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Interfaces\Media;
use  tomvo\TelegramBot\Media\Base;

use tomvo\TelegramBot\Traits\Uploadable;

use tomvo\TelegramBot\Exceptions\FileNotExists;
use tomvo\TelegramBot\Exceptions\ValidationException;

class Audio extends BaseUploadable implements Media {
	use Uploadable;

	public function __construct(Array $data)
	{
		parent::__construct($data);

		$this->setTransformKey('audio');
	}

	public function getSendEndpoint()
	{
		return 'sendAudio';
	}

}