<?php

class HomeController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		$title		= "Romy Art - Eshop de tote bags et décorations";
		$content	= "Romy Art par Romane Jacquemin - Illustrations et décorations en flash ou customisation, tote bags, planches de surf, ardoises, décoration murale et vitrines";

		return ["FlashBag" => new FlashBag(), "title" => $title, "content" => $content];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{

	}
}