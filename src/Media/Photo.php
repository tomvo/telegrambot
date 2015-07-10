<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Interfaces\Media;
use  tomvo\TelegramBot\Media\Base;

use tomvo\TelegramBot\Traits\Uploadable;

use tomvo\TelegramBot\Exceptions\FileNotExistsException;
use tomvo\TelegramBot\Exceptions\ValidationException;

class Photo extends BaseUploadable implements Media {
	use Uploadable;

	public function __construct(Array $data)
	{
		parent::__construct($data);

		$this->setTransformKey('photo');
	}


	public function getSendEndpoint()
	{
		return 'sendPhoto';
	}

}