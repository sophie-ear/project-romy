<?php

class PostController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		// RETOUR ACCUEIL POUR NON ADMIN
		$session = new UserSession();

		if($session->isAdmin() === false){

			$flashBag = new FlashBag();
			$flashBag->add("Veuillez vous connecter avec un compte administrateur pour accéder à cette page.");
			$http->redirectTo("/");
		}

		// APPEL DES PRODUITS DEPUIS LA BDD dans ProductModel
		$productModel = new ProductModel();
		$products = $productModel->listAll();
		
		$title = "RomyArt - Liste des articles";

		return ["FlashBag" => new FlashBag(), "products" => $products, "title" => $title];
	}


	public function httpPostMethod(Http $http, array $formFields)
	{
		//On vérifie avec des if sur quel button on a cliqué (button est le name des button dans HomeView, BasketView, PostView)
		$button = $formFields["button"];

		// SUPPRESSION DU PRODUIT
		if($button == "deleteItem"){

			//On récupère l'id du produit qu'on veut supprimer du site
			$id = $formFields["product"];
			
			// Appel de la fonction qui permet de supprimer le produit de la bdd
			$delete = new AdminModel();
			$delete->deleteItem($id);
			$http->redirectTo("/admin/post");
		}

	}

}