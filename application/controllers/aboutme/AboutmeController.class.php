<?php

class AboutmeController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		$title		= "Romy Art - About Me";
		$content	= "Romy Art par Romane Jacquemin - Je réalise des illustrations sur mesure et sur différents supports. Contactez-moi pour concrétiser vos envies !";
		
		return ["title" => $title, "content" => $content];
	}


	public function httpPostMethod(Http $http, array $formFields)
	{

	}
}

?>