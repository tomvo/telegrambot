<?php namespace tomvo\TelegramBot\Traits;

use tomvo\TelegramBot\Exceptions\DefinitionException;

trait Fillable {
	public function fill(Array $data)
	{
		if(sizeof($data) === 0) return; 
		if(!property_exists($this, 'fillable')) throw new DefinitionException("Property 'fillable' needs to be defined in class the implements Fillable trait");

		foreach($data as $key => $value){
			if(in_array($key, $this->fillable)){
				$this->{$key} = $value;
			}
		}
	}

	public function map()
	{
		if(isset($this->map) && is_array($this->map) && sizeof($this->map) > 0){
			foreach($this->map as $property => $className){
				if(!isset($this->{$property})) continue; //throw new DefinitionException("Property {$property} set in map not available as property of class");


				//If the classname is defined as an array it means it expects an array of the class type
				if(is_array($className)){
					if(sizeof($className) == 0) continue;

					$className = array_shift($className);
					if(class_exists($className) && sizeof($this->{$property}) > 0){

						$objects = $this->{$property};

						$this->{$property} = [];
						foreach($objects as $object){
							$cls = new $className($object);
							array_push($this->{$property}, $cls);
						}
					}
				}else{
					if(class_exists($className)){
						$this->{$property} = new $className( $this->{$property} );
					}
				}
			}
		}
	}
}