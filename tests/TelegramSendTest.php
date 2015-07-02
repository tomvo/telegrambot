<?php
 
use tomvo\TelegramBot\Api;

use tomvo\TelegramBot\Media\Message;
use tomvo\TelegramBot\Media\Photo;
use tomvo\TelegramBot\Media\Video;
use tomvo\TelegramBot\Media\Sticker;
use tomvo\TelegramBot\Media\Document;
use tomvo\TelegramBot\Media\Location;
use tomvo\TelegramBot\Media\ChatAction;
use tomvo\TelegramBot\Media\Audio;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

 
class TelegramSendTest extends PHPUnit_Framework_TestCase {
	protected $api = null;
	protected $client = null;


	protected function setUp()
	{

	}
	public function testSendMessage()
	{
		$mock = new MockHandler([
		    new Response(200, [

		    ]),
		]);


		$handler = HandlerStack::create($mock);
		
	}
 
}