<?php

class OrderController {
	
	public function httpGetMethod(Http $http, array $queryFields)
	{
		//RECUPERATION ID CUSTOMER
		$userSession = new UserSession();
		$userID = $userSession->getID();
		
		//RECUPERATION TTC ET HT
		$panier = new BasketSession();
		$ttc = $panier->getTTC();
		
		// S'il y a une virgule dans le prix on remplace par un . ensuite on arrondit à deux chiffres après la virgule
		$ht = round(str_replace(",", ".", $ttc*0.8), 2);
		
		//RECUPERATION DES PRODUITS
		$products = $panier->getProducts();
		
		//PUSH DANS ORDERMODEL
		$orderModel = new OrderModel();
		$orderModel->createOrder($userID, $ttc, $ht, $products);
		
		$http->redirectTo("/account");
	}
	
	public function httpPostMethod(Http $http, array $formFields)
	{
		
	}
}