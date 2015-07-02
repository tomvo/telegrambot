<?php namespace tomvo\TelegramBot;

use Valitron\Validator;

use tomvo\TelegramBot\Api;

use tomvo\TelegramBot\Traits\Fillable;
use tomvo\TelegramBot\Interfaces\Media;

//Media objects
use tomvo\TelegramBot\Media\Message;
use tomvo\TelegramBot\Media\Photo;
use tomvo\TelegramBot\Media\Document;
use tomvo\TelegramBot\Media\Location;
use tomvo\TelegramBot\Media\Video;
use tomvo\TelegramBot\Media\Sticker;
use tomvo\TelegramBot\Media\Audio;

//Container objects
use tomvo\TelegramBot\Containers\GroupChat;
use tomvo\TelegramBot\Containers\PhotoSize;
use tomvo\TelegramBot\Containers\User;

class ResponseMessage {
    use Fillable;

    protected $fillable = [
        'message_id',
        'from',
        'date',
        'chat',
        'forward_from',
        'forward_date',
        'reply_to_message',
        'text',
        'audio',
        'document',
        'photo',
        'sticker',
        'video',
        'contact',
        'location',
        'new_chat_participant',
        'left_chat_participant',
        'new_chat_title',
        'new_chat_photo',
        'delete_chat_photo',
        'group_chat_created',
    ];

    protected $rules = [];
    protected $map = [
        'from' => 'tomvo\TelegramBot\Containers\User',
        'message' => 'tomvo\TelegramBot\Media\Message',
        'audio' => 'tomvo\TelegramBot\Media\Audio',
        'document' => 'tomvo\TelegramBot\Media\Document',
        'photo' => ['tomvo\TelegramBot\Containers\PhotoSize'],
        'sticker' => 'tomvo\TelegramBot\Media\Sticker',
        'video' => 'tomvo\TelegramBot\Media\Video',
        'contact' => 'tomvo\TelegramBot\Media\Contact',
        'location' => 'tomvo\TelegramBot\Media\Location',
        'left_chat_participant' => 'tomvo\TelegramBot\Containers\User',
        'new_chat_participant' => 'tomvo\TelegramBot\Containers\User',
        'new_chat_photo' => ['tomvo\TelegramBot\Containers\PhotoSize'],
    ];

    protected $mediaType;
    
    //Contains a reference to an instantiated API object. This is kept here to be able to directly respond to this
    //response.
    protected $api; 

	public function __construct($data, Api &$api)
	{
		//Make sure we're always dealing with an assoc array
        if($data instanceof \stdClass){
            $data = json_decode(json_encode($data), true);
        }

        $this->api = $api;

        $this->fill($data);
        $this->parse();
	}	


    protected function parse()
    {
        $this->date = \DateTime::createFromFormat('U', $this->date);
        
        //Determine who sent this message or where the message was sent to
        //The chat property can either be a User or a GroupChat type, determine this here and set the property accordingly
        if(isset($this->chat->firstname)){
            //If the firstname is set then the chat is a user
            $this->map['chat'] = 'tomvo\TelegramBot\Containers\User';
        }else{
            $this->map['chat'] = 'tomvo\TelegramBot\Containers\GroupChat';
        }

        $this->map();

        //Start to determine the media type that is incoming, store the media in the media property so it can be retrieved later on
        if(isset($this->text)){
            $this->mediaType = 'message';
        }elseif(isset($this->audio)){
            $this->mediaType = 'video';
        }elseif(isset($this->document)){
            $this->mediaType = 'document';
        }elseif(isset($this->photo)){
            $this->mediaType = 'photo';
        }elseif(isset($this->sticker)){
            $this->mediaType = 'sticker';
        }elseif(isset($this->video)){
            $this->mediaType = 'video';
        }elseif(isset($this->contact)){
           $this->contact;
            $this->mediaType = 'contact';
        }elseif(isset($this->location)){
            $this->mediaType = 'location';
        }
    }

    public function getMedia()
    {
        return $this->{$this->mediaType};
    }

    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * Respond to an incoming message, it will retrieve the sender information from the chat property
     * 
     * @author Tom van Oorschot <tom@customerconnect.de>
     * @date   2015-07-01T23:17:18+0100
     * @param  Media                    $media [description]
     * @return [type]                          [description]
     */
    public function respond(Media $media)
    {
        return $this->api->send($this->chat->id, $media);
    }

    /**
     * Send an 'action' in preparation for a next response
     * 
     * @author Tom van Oorschot <tom@customerconnect.de>
     * @date   2015-07-02T19:12:08+0100
     * @return [type]                   [description]
     */
    public function action($action)
    {
        $this->api->sendAction($action);

        return $this;
    }
}
