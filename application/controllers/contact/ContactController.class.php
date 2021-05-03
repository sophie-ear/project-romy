<?php

class ContactController {
	public function httpGetMethod(Http $http, array $queryFields)
	{
		
		$title		= "Romy Art - Page de contact";
		$content	= "Pour me contacter : romanejacquemin@yahoo.fr ou sur ma page Instagram @romyartwork";
		
		return ["title" => $title, "content" => $content];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
		
	}
}