<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Interfaces\Media;
use  tomvo\TelegramBot\Media\Base;

use tomvo\TelegramBot\Traits\Respondable;
use tomvo\TelegramBot\Traits\Uploadable;

use tomvo\TelegramBot\Exceptions\FileNotExistsException;
use tomvo\TelegramBot\Exceptions\ValidationException;

class Video extends Base implements Media {
	use Respondable, Uploadable;

	protected $fillable = [
		'file', 
		'file_id',
		
		'width',
		'height',
		'duration',
		'thumb',
		'mime_type',
		'file_size',
		'caption'
	];

	protected $required = ['file'];
	protected $map = [
		'thumb' =>  'tomvo\TelegramBot\Containers\PhotoSize',
	];

	protected $isFile = false;

	public function __construct(Array $data)
	{
		parent::__construct($data);

		if(empty($this->file) && empty($this->file_id)) throw new ValidationException('Either file or file_id need to be provided');

		if(isset($this->file)){
			if(!$this->isFile($this->file)) throw new ValidationException('Provided file is not found in the filesystem');
			
			$this->isFile = true;
		}

		$this->setTransformKey('video');
	}

	public function getFormMethod()
	{
		if(!$this->isFile){
			return \GuzzleHttp\RequestOptions::FORM_PARAMS;
		}else{
			return \GuzzleHttp\RequestOptions::MULTIPART;	
		}
	}

	public function getSendEndpoint()
	{
		return 'sendVideo';
	}
}

