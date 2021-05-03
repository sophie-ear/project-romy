<?php

class AdminController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		// RETOUR ACCUEIL POUR NON ADMIN
		$session = new UserSession();

		if($session->isAdmin() === false){

			$flashBag = new FlashBag();
			$flashBag->add("Veuillez vous connecter avec un compte administrateur pour accéder à cette page.");
			$http->redirectTo("/");
		}

		$orderModel = new OrderModel();
		$orders = $orderModel->listOrders();
		$status = $orderModel->getOrderStatus();
		
		$title = "RomyArt - Administation";

		return ["FlashBag" => new FlashBag(), "orders" => $orders, "status" => $status, "title" => $title];
	}


	public function httpPostMethod(Http $http, array $formFields)
	{
		//On vérifie avec des if sur quel button on a cliqué (button est le name des button dans HomeView, BasketView, PostView, AdminView)
		$button = $formFields["button"];

		if($button == "editOrderStatus"){

			$orderID	= $formFields["OrderID"];
			$newStatus	= $formFields["status"];

			$changeStatus = new OrderModel();
			$changeStatus->changeOrderStatus($orderID, $newStatus);
			$http->redirectTo("/admin");
		}

		if($button == "editUserStatus"){

			$userID 	= $formFields["UserID"];
			$newStatus	= $formFields["userStatus"];

			$changeStatus = new AdminModel();
			$changeStatus->changeUserStatus($userID, $newStatus);
			$http->redirectTo("/admin/listusers");
		}

		if($button == "deleteUser"){
			
			$userID = $formFields["UserID"];

			$delete = new AdminModel();
			$delete->deleteUser($userID);
			$http->redirectTo("/admin/listusers");
		}

	}
}

?>