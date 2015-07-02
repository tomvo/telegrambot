<?php namespace tomvo\TelegramBot\Containers;


class PhotoSize extends Base{
	protected $fillable = ['file_id', 'width', 'height', 'file_size'];
}