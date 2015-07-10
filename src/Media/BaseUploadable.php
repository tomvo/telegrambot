<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Exceptions\DefinitionException;
use tomvo\TelegramBot\Exceptions\ValidationException;

class BaseUploadable extends Base{
	protected $isFile;

	public function __construct($data)
	{
		parent::__construct($data);

		if(empty($this->file) && empty($this->file_id)) throw new ValidationException('Either file or file_id need to be provided');

		if(isset($this->file)){
			if(!$this->isFile($this->file)) throw new ValidationException('Provided file is not found in the filesystem');
			
			$this->isFile = true;
		}
	}
	/**
	 * Use simple check to see if a string file is a file URI or something else. 
	 * This is used for making a decision to upload a file or to resend a file using a file_id
	 * 
	 * @author Tom van Oorschot <tom@customerconnect.de>
	 * @date   2015-07-06T12:28:28+0100
	 * @param  [type]                   $value [description]
	 * @return boolean                         [description]
	 */
	public function isFile($value)
	{
		//Check if the photo is a file or a fileId and set the local isFile property accordingly
		//This check is later used to decide between a multipart form upload or just a regular form post
		if ( !parse_url($value, PHP_URL_SCHEME)) {
			if(file_exists('file://' . $value)){
				return true;
			}
		}

		return false;
	}

	public function getFormMethod()
	{
		if(!$this->isFile){
			return \GuzzleHttp\RequestOptions::FORM_PARAMS;
		}else{
			return \GuzzleHttp\RequestOptions::MULTIPART;	
		}
	}
}