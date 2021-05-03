<?php

class EmptyController {
	
	public function httpGetMethod(Http $http, array $queryFields)
	{
		//SUPPRESSION DU PANIER 
		$panier = new BasketSession();
		$panier->destroyBasket();
		$http->redirectTo("/basket");
		
	}
	
	public function httpPostMethod(Http $http, array $formFields)
	{

	}
}