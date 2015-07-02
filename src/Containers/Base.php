<?php namespace tomvo\TelegramBot\Containers;

use tomvo\TelegramBot\Traits\Fillable;

class Base {
	use Fillable;

	public function __construct(Array $data)
	{
		$this->fill($data);
	}


}