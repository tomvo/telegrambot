<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Interfaces\Media;
use  tomvo\TelegramBot\Media\Base;

use tomvo\TelegramBot\Traits\Uploadable;

use tomvo\TelegramBot\Exceptions\FileNotExistsException;
use tomvo\TelegramBot\Exceptions\ValidationException;

class Video extends BaseUploadable implements Media {
	use Uploadable;

	protected $fillable = [
		'width',
		'height',
		'duration',
		'thumb',
		'mime_type',
		'file_size',
		'caption'
	];

	protected $map = [
		'thumb' =>  'tomvo\TelegramBot\Containers\PhotoSize',
	];

	public function __construct(Array $data)
	{
		parent::__construct($data);

		$this->setTransformKey('video');
	}

	public function getSendEndpoint()
	{
		return 'sendVideo';
	}
}

