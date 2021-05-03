<?php

class EditController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		// RETOUR ACCUEIL POUR NON ADMIN
		$session = new UserSession();

		if($session->isAdmin() === false){

			$flashBag = new FlashBag();
			$flashBag->add("Veuillez vous connecter avec un compte administrateur pour accéder à cette page.");
			$http->redirectTo("/");
		}

		// Récupération de l'id du produit à modifier
		$id = $queryFields["ProductID"];

		// Récupération des informations du produit
		$productModel = new ProductModel();
		$product = $productModel->getProductInfo($id);
		
		$title = "RomyArt - Modification de ".$product["ProductName"];

		return ["product"=>$product, "_form" => new ItemForm(), "title" => $title];
	}


	public function httpPostMethod(Http $http, array $formFields)
	{
		//On vérifie avec des if sur quel button on a cliqué (button est le name des button dans HomeView, BasketView, PostView)
		$button = $formFields["button"];

		if($button == "updateItem"){

			//On récupère l'id du produit qu'on veut modifier du site
			$id 			= $formFields["product"];

			// Récupération des informations
			$name 			= htmlspecialchars($formFields["ProductName"]);
			$description 	= htmlspecialchars($formFields["ProductDescription"]);
			$price 			= htmlspecialchars(round(str_replace(",", ".", $formFields["ProductPrice"]), 2));
			$qty 			= htmlspecialchars($formFields["quantity"]);

			$uploaddir	= 'application/www/images/shop/';
			$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
			move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
			$image		= htmlspecialchars(basename($_FILES['userfile']['name']));


			try {
				// Appel de la fonction qui permet de modifier le produit dans la bdd
				$update = new AdminModel();
				$update->updateItem($id, $name, $description, $price,  $qty, $image);
				$http->redirectTo("/admin/post");

			} catch (Exception $exception) {

				// Afficher un message d'erreur
				$form = new ItemForm();
				$form->bind($formFields); // Pré-remplissage du formulaire
				$form->setErrorMessage($exception->getMessage());

				// Récupération des informations du produit pour le bind
				$productModel = new ProductModel();
				$product = $productModel->getProductInfo($id);

				return ["_form" => $form, "product" => $product];
			}
		}
	}
}