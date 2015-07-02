<?php namespace tomvo\TelegramBot\Interfaces;

interface Media {
	public function transform();
	public function getSendEndpoint();
	public function getFormMethod();
}