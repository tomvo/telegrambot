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

class ResponseMessageTest extends SendMediaBaseTest {
	protected function loadResponse($file=null){
		if(empty($file)) $file = 'sendMessageResponse.json';

		$stream = Psr7\stream_for(fopen(__DIR__ . '/responses/' . $file, 'r'));
		
		$response = new Response(200, ['Content-Type' => 'application/json'], $stream);
		//Append another messsage response
		self::$handler->append($response);
	}


	public function testResponseIsResponseMessage($value='')
	{
		$this->loadResponse();

		$media = new Message([
			'text' => 'This is a test message'
		]);

		$response = self::$api->send(1, $media);

		$this->assertInstanceOf('tomvo\TelegramBot\ResponseMessage', $response);
	}


	public function testRespondWithMessage()
	{
		$this->loadResponse();

		$media = new Message([
			'text' => 'This is a test message'
		]);

		$response = self::$api->send(1, $media);
		$this->assertEquals('message', $response->getMediaType());

		$message = $response->getMedia();

		$this->assertInstanceOf('tomvo\TelegramBot\Media\Message', $message);

		//Load a new response
		$this->loadResponse();

		$newResponse = $response->respond(new Message([
			'text' => 'This is a test message'
		]));

		$this->assertEquals('message', $response->getMediaType());
		$this->assertInstanceOf('tomvo\TelegramBot\Media\Message', $message);
	}

	public function testChatIsGroupChat()
	{
		$this->loadResponse();

		$media = new Message([
			'text' => 'This is a test message'
		]);

		$response = self::$api->send(1, $media);
		$this->assertInstanceOf('tomvo\TelegramBot\Containers\GroupChat', $response->chat);
	}

	public function testFrom()
	{
		$this->loadResponse();

		$media = new Message([
			'text' => 'This is a test message'
		]);

		$response = self::$api->send(1, $media);
		$this->assertInstanceOf('tomvo\TelegramBot\Containers\User', $response->from);
	}

	public function testIsDateTime()
	{
		$this->loadResponse();

		$media = new Message([
			'text' => 'This is a test message'
		]);

		$response = self::$api->send(1, $media);
		$this->assertInstanceOf('DateTime', $response->date);
	}

	public function testChatIsUser()
	{
		$this->loadResponse('sendMessageResponseUser.json');

		$media = new Message([
			'text' => 'This is a test message'
		]);

		$response = self::$api->send(1, $media);
		$this->assertInstanceOf('tomvo\TelegramBot\Containers\User', $response->chat);
	}
 
}