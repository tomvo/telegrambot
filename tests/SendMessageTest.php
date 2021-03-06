<?php
use tomvo\TelegramBot\Api;
use tomvo\TelegramBot\ResponseMessage;

use tomvo\TelegramBot\Media\Message;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;

use GuzzleHttp\Psr7;

class SendMessageTest extends SendMediaBaseTest {
	protected function loadResponse(){
		$stream = Psr7\stream_for(fopen(__DIR__ . '/responses/sendMessageResponse.json', 'r'));
		
		$response = new Response(200, ['Content-Type' => 'application/json'], $stream);
		//Append another messsage response
		self::$handler->append($response);
	}



	public function testMediaType()
	{
		$this->loadResponse();

		$media = new Message([
			'text' => 'This is a test message'
		]);

		$response = self::$api->send(1, $media);

		$this->assertEquals('message', $response->getMediaType());
		$this->assertInstanceOf('tomvo\TelegramBot\Media\Message', $response->getMedia());
	}

	public function testMediaTypeIsMessage()
	{
		$this->loadResponse();

		$media = new Message([
			'text' => 'This is a test message'
		]);

		$response = self::$api->send(1, $media);

		$this->assertEquals('message', $response->getMediaType());
		$this->assertInstanceOf('tomvo\TelegramBot\Media\Message', $response->getMedia());
	}

	public function testReturnedMessageContainsInitialMessage()
	{
		$this->loadResponse();

		$media = new Message([
			'text' => 'This is a test message'
		]);

		$response = self::$api->send(1, $media);

		$this->assertEquals('This is a test message', $response->getMedia()->text);
	}


}