<?php
 
use tomvo\TelegramBot\Api;
use tomvo\TelegramBot\ResponseMessage;

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
use GuzzleHttp\Psr7\Stream;

use GuzzleHttp\Psr7;

class SendActionTest extends PHPUnit_Framework_TestCase {
	public static $api;
	public static $handler;

	protected $data = '{

		}';

	protected function loadResponse($data){
		$stream = Psr7\stream_for($data);
		
		$response = new Response(200, ['Content-Type' => 'application/json'], $stream);
		//Append another messsage response
		self::$handler->append($response);
	}

	public static function setUpBeforeClass()
	{
		date_default_timezone_set('Europe/Amsterdam');

		self::$handler = new MockHandler();
		$handler = HandlerStack::create(self::$handler);

		self::$api = new Api(null, $handler);	
	}

//['upload_photo', 'record_video', 'record_audio', 'upload_document', 'find_location']
	public function testSendUploadPhotoAction()
	{
		// $this->loadResponse($this->data);

		// $response = self::$api->sendAction(123, 'upload_photo');

		// $this->assertEquals('message', $response->getMediaType());
		// $this->assertInstanceOf('tomvo\TelegramBot\Media\Message', $response->getMedia());
	}

}