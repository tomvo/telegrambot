<?php

use tomvo\TelegramBot\Api;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class sendMediaBaseTest extends PHPUnit_Framework_TestCase {
	public static $api;
	public static $handler;

	public static function setUpBeforeClass()
	{
		date_default_timezone_set('Europe/Amsterdam');

		self::$handler = new MockHandler();
		$handler = HandlerStack::create(self::$handler);

		self::$api = new Api(null, $handler);	
	}

}