<?php

class DetailsController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		// On récupère l'id de la commande ainsi que l'ID de l'utilisteur à laquelle elle est liée
		$orderID	= $queryFields["OrderID"];
		$userID 	= $queryFields["UserID"];
		$orderModel = new OrderModel();
		$details	= $orderModel->listDetails($orderID);

		// RETOUR ACCUEIL POUR NON ADMIN ET SI CE N'EST PAS LA COMMANDE DE CET USER
		$session = new UserSession();

		if(($session->islogged() == false || $session->getID() != $userID) && $session->isAdmin() == false){

			$flashBag = new FlashBag();
			$flashBag->add("Vous n'avez pas accès à cette page.");
			$http->redirectTo("/");
		}
		
		$title = "Détails de la commande ".$orderID;
		
		return ["FlashBag" => new FlashBag(), "details" => $details, "orderID" => $orderID, "title" => $title];
	}


	public function httpPostMethod(Http $http, array $formFields)
	{
		
	}
}

?>