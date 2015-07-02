### WORK IN PROGRESS - DO NOT USE

__TODO__

- [ ] TESSSSSTTTSSS!!
- [ ] Comment out the code some more
- [ ] Validation on the input from both developer and Telegram API
- [ ] Self documenting the media properties, how to know which properties to set when instantiating a Media object


## Telegram Bot API Wrapper written in PHP

[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://github.com/tomvo/telegrambot/blob/master/LICENSE.md)

A wrapper for the Telegram Bot API using the latest version of Guzzle.


### Why use this library?

- Built on Guzzle

	I've built this library on top of Guzzle which gives solid support in dealing with the Telegram API. It makes it easy to upload files using Multipart or to just do a simple urlencoded form request and allows tests to be easy mockable.
- Just use `->send([chat_id], [media item])`

	You can pass any of the Media objects into the API send method and off you go!
- Chain 'm up! 

	All responses are an instance of `ResponseMessage` which is chainable. You can `respond()` directly to an incoming update.

### Getting an instance

To get in instance of the API 

### Sending something

The library is not a 1:1 copy of the Telegram API but on provides an easy to use abstracted interface for sending different kind of `Media` objects.
In order to send any of the supported media types


__Sending a message__

```php
$message = new Message([
	'text' => 'tom'
]);

$api->send([chat_id], $message);
```

__Sending something else__

No worries! You can pass in any of the available media types to the `send` method to send it to a Chat or User.

```php
$media = new Location([
	'latitude' => '51.4352381',
	'longitude' => '5.4635988'
]);

$media = new Message([
	'text' => 'Yo whatup!?'
]);

$media = new Photo([
	'file' => '/path/to/image.jpg'
	//'file_id' => '[FILE_ID]'
]);

$media = new Video([
	'file' => '/path/to/video.mp4'
	//'file_id' => '[FILE_ID]'
]);

$media = new Sticker([
	'file' => '/path/to/sticker.webp'
	//'file_id' => '[FILE_ID]'
]);


$media = new Audio([
	'file' => '/path/to/audio.ogg'
	//'file_id' => '[FILE_ID]'
]);

$api->send([chat_id], $media);

```

What's all that file magic? Well you can just pass in either a `file_id` of an existing Telegram File or set the `file` property with a string representing a file on your local filesystem. The library will recognize this and uploads the file when necessary.

__Support Media Types__

...

### Exceptions

When invoking the API class methods and in case of a Client or Server exception the API will not try to catch these. It's up to you to catch the Guzzle exceptions.
You can use this:

```php
try{
	//....
} catch (\Exception $e) {
	if($e instanceof ClientException || $e instanceof ServerException){
		//Guzzle exception
	}
}
```


### Running a webhook server

If you want to accept incoming messages from Telegram include this library in a framework or simply parse incoming requests yourself. Included in the examples is a basic test server you can run to test out the handling functionality.

You can pass incoming requests to the library using the `tomvo\TelegramBot\Api->handleUpdate()` method. This method accepts a associative array that can either be the direct response from Telegram having the `result` key or a drilled down `Message` object.


*Super simple server example*
```php
	$api = new tomvo\TelegramBot\Api('YOUR_KEY');

	$rawData = file_get_contents('php://input');
	$json = json_decode($rawData, true);

	//This will return a ResponseMessage object
	$response = $api->handleUpdate($json);

```

Put the above in an `index.php` file and and run `php -S 8000` to load the server. If you want to run a test server and you're running OSX you can use [https://ngrok.com/](ngrok) to tunnel to localhost. First start your php webserver and then run `ngrok http 8000` to create a ngrok public url. You can then point Telegram to post messages to your webserver as a webhook.

*Set a webhook url*

You can use a one-off command to set the Telegram webhook to point to your test server.
```php
	$api = new tomvo\TelegramBot\Api('YOUR_KEY');
	$api->setWebhook('[your ngrok url]');
```

### All cool and dandy, but how do I create a bot?

Just ask the [BotFather](https://core.telegram.org/bots)!



