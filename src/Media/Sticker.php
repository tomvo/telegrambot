<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Interfaces\Media;
use  tomvo\TelegramBot\Media\Base;

use tomvo\TelegramBot\Traits\Uploadable;
use tomvo\TelegramBot\Exceptions\FileNotExists;

class Sticker extends BaseUploadable implements Media {
	use Uploadable;

	protected $fillable = [
		'width',
		'height',
		'thumb',
		'file_size',
	];

	protected $map = [
		'thumb' =>  'tomvo\TelegramBot\Containers\PhotoSize',
	];

	public function __construct(Array $data)
	{
		parent::__construct($data);

		$this->setTransformKey('sticker');
	}

	public function getSendEndpoint()
	{
		return 'sendSticker';
	}
}