<?php namespace tomvo\TelegramBot\Media;

use tomvo\TelegramBot\Traits\Fillable;

use tomvo\TelegramBot\Exceptions\DefinitionException;

class Base {
	use Fillable;

	//All media items have at least these properties, the constructor will combine these with extra properties set
	//in the media object's $fillable property
	protected $coreFillable =  ['file', 'file_id', 'reply_to_message_id', 'reply_markup'];

	public function __construct(Array $data)
	{
		//Merge the core fillable items with extra properties set in the media object
		if(isset($this->fillable)){
			$this->fillable = array_unique(array_merge($this->coreFillable, $this->fillable));
		}else{
			$this->fillable = $this->coreFillable;
		}

		//load the available data into the fillable properties
		$this->fill($data);

		//Map the properties onto the classes, the mapping is defined in the mappable propert
		$this->map();
	}
}