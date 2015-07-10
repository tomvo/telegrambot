<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Interfaces\Media;
use tomvo\TelegramBot\Traits\Uploadable;

use tomvo\TelegramBot\Exceptions\FileNotExistsException;
use tomvo\TelegramBot\Exceptions\ValidationException;


class Document extends BaseUploadable implements Media {
	use Uploadable;

	protected $fillable = [
		'thumb',
		'file_name',
		'mime_type',
		'file_size',
	];

	protected $map = [
		'thumb' =>  'tomvo\TelegramBot\Containers\PhotoSize',
	];

	protected $required = ['document'];

	public function __construct(Array $data)
	{
		parent::__construct($data);

		$this->setTransformKey('document');
	}

	public function getSendEndpoint()
	{
		return 'sendDocument';
	}

}