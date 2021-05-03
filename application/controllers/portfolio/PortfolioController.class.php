<?php

class PortfolioController {
	
	public function httpGetMethod(Http $http, array $queryFields)
	{
		$title		= "RomyArt - Portfolio";
		$content	= "RomyArt - Retrouvez ici des exemples de mes travaux : murs, vitrines, décorations d'intérieurs, flash pour des planches de surfs ainsi que des cartes de restaurant";
		
		return ["title" => $title, "content" => $content];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
	
	}
}