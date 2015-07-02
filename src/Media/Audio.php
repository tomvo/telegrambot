<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Interfaces\Media;
use  tomvo\TelegramBot\Media\Base;

use tomvo\TelegramBot\Traits\Uploadable;

use tomvo\TelegramBot\Exceptions\FileNotExists;
use tomvo\TelegramBot\Exceptions\ValidationException;

class Audio extends Base implements Media {
	use Uploadable;

	protected $fillable = ['file', 'file_id'];
	protected $required = ['from', 'date'];

	protected $isFile = false;

	public function __construct(Array $data)
	{
		parent::__construct($data);

		if(empty($this->file) && empty($this->file_id)) throw new ValidationException('Either file or file_id need to be provided');

		if(isset($this->file)){
			if(!$this->isFile($this->file)) throw new ValidationException('Provided file is not found in the filesystem');
			
			$this->isFile = true;
		}

		$this->setTransformKey('audio');
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
		return 'sendAudio';
	}

}