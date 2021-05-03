<?php

class ListusersController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		// RETOUR ACCUEIL POUR NON ADMIN
		$session = new UserSession();

		if($session->isAdmin() === false){

			$flashBag = new FlashBag();
			$flashBag->add("Veuillez vous connecter avec un compte administrateur pour accéder à cette page.");
			$http->redirectTo("/");
		}

		$userModel = new UserModel();
		$users	= $userModel->listUsers();
		$status = $userModel->getUserStatus();
		
		$title	= "RomyArt - Liste des utilisateurs";

		return ["FlashBag" => new FlashBag(), "users" => $users, "status" => $status, "title" => $title];
	}


	public function httpPostMethod(Http $http, array $formFields)
	{

	}
}

?>