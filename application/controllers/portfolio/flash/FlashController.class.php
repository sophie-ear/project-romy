<?php

class FlashController {
	
	public function httpGetMethod(Http $http, array $queryFields)
	{
		$title		= "RomyArt - Exemples de flash sur planches de surfs";
		$content	= "RomyArt - Voici des exemples de flash sur planches de surfs";
		
		return ["title" => $title, "content" => $content];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
	
	}
}