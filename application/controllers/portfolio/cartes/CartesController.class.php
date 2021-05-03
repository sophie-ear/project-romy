<?php

class CartesController {
	
	public function httpGetMethod(Http $http, array $queryFields)
	{
		$title		= "RomyArt - Exemples de cartes de restaurants";
		$content	= "RomyArt - Voici des exemples de cartes de restaurants";
		
		return ["title" => $title, "content" => $content];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
	
	}
}