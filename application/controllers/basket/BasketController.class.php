<?php

class BasketController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		// VERIFICATION CONNEXION
		$session = new UserSession();
		if($session->isLogged() == false){
			
			// Redirection vers la page de connexion
			$http->redirectTo("/user/login");
		}
		
		// RECUPERATION DES PRODUITS
		$panierSession = new BasketSession();
		$panier = $panierSession->getProducts();
		
		$title = "RomyArt - Mon Panier";
			
		return ["FlashBag" => new FlashBag(), "basket"=>$panier, "title" => $title];
	}


	public function httpPostMethod(Http $http, array $formFields)
	{
		//On vérifie avec des if sur quel button on a cliqué
		$button = $formFields["button"];
		
		// AJOUT DU PRODUIT
		if($button == "addBasket"){
			
			// On récupère les données noms du menu dans ShopView
			$idSelected  = $formFields["ProductID"];
			$qtySelected = $formFields["ProductQty"];

			// Création du tableau qui récupère les infos des produits
			$productModel = new ProductModel();
			$product = $productModel->getProductInfo($idSelected);
		
			$name   = $product["ProductName"];
			$price  = round(str_replace(",", ".", $product["ProductPrice"]), 2);
			$image  = $product["ProductImage"];
			$qtyMax = $product["ProductQty"];
			
			// Envoi vers BasketSession
			$basket = new BasketSession();

			// Permet de vérifier si la quantité max est dépassée
			if($basket->atMax($idSelected, $qtySelected) == true){
				
				$flashBag = new FlashBag();
				$flashBag->add("Vous pouvez commander au maximum ".$qtyMax." ".$name);
			}
			else{
				$basket->addProduct($idSelected, $qtySelected, $qtyMax, $name, $price, $image);
			}
			
			$http->redirectTo("/shop"); 

		}
		
		// SUPPRESSION DU PRODUIT
		if($button == "deleteProduct"){
			
			//On récupère l'id du produit qu'on veut supprimer du panier dans BasketView
			$id = $formFields["product"];
			
			//Appel de la fonction qui permet de supprimer le produit
			$basket = new BasketSession();
			$basket->removeProduct($id);
			$http->redirectTo("/basket");
		}

		// MODIFICATION DE LA QUANTITE DANS LE PANIER
		if($button == "editQty"){

			$id 	= $formFields["product"];
			$qty	= $formFields["quantity"];

			//Appel de la fonction qui permet de modifier la quantité du produit dans le panier
			$basket = new BasketSession();
			$basket->modifyQty($id, $qty);
			$http->redirectTo("/basket");
		}
		
	}

}

?>