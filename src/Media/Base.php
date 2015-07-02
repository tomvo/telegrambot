<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Traits\Fillable;

use tomvo\TelegramBot\Exceptions\DefinitionException;

class Base {
	use Fillable;

	public function __construct(Array $data)
	{
		$this->fill($data);
		$this->map();
	}

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
}