<?php

class MurController {
	
	public function httpGetMethod(Http $http, array $queryFields)
	{
		$title		= "RomyArt - Exemples de murs";
		$content	= "RomyArt - Voici des exemples de décors murals et de vitrines";
		
		return ["title" => $title, "content" => $content];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
		
	}
}