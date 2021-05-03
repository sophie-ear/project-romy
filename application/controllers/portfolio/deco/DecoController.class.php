<?php

class DecoController {
	
	public function httpGetMethod(Http $http, array $queryFields)
	{
		$title		= "RomyArt - Exemples de décorations d'intérieur";
		$content	= "RomyArt - Voici des exemples de décorations d'intérieur";
		
		return ["title" => $title, "content" => $content];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
	
	}
}