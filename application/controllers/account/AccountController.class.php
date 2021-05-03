<?php

class AccountController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		// VERIFICATION CONNEXION
		$session = new UserSession();
		if($session->isLogged() === false){
			
			// Redirection vers la page de connexion
			$http->redirectTo("/user/login");
		}

		$userID = $session->getID();

		// RECUPERATION DES COMMANDES DE CET UTILISATEUR
		$orderModel = new OrderModel();
		$orders = $orderModel->listUserOrders($userID);

		// RECUPERATION DES INFOS DE CET UTILISATEUR
		$userModel = new UserModel();
		$info = $userModel->listOneUser($userID);
		
		$title = "Romy Art - Mon compte";

		return ["FlashBag" => new FlashBag(), "orders" => $orders, "info" => $info, "title" => $title];
	}


	public function httpPostMethod(Http $http, array $formFields)
	{

	}
}

?>