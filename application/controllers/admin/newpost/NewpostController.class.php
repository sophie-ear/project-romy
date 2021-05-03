<?php

class NewpostController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		// RETOUR ACCUEIL POUR NON ADMIN
		$session = new UserSession();

		if($session->isAdmin() === false){

			$flashBag = new FlashBag();
			$flashBag->add("Veuillez vous connecter avec un compte administrateur pour accéder à cette page.");
			$http->redirectTo("/");
		}
		
		$title = "RomyArt - Nouvel article";

		return ["_form" => new ItemForm(), "title" => $title];
	}


	public function httpPostMethod(Http $http, array $formFields)
	{
		$ProductName		= htmlspecialchars($formFields["ProductName"]);
		$ProductDescription = htmlspecialchars($formFields["ProductDescription"]);
		// S'il y a une virgule dans le prix on remplace par un . ensuite on arrondit à deux chiffres après la virgule
		$ProductPrice		=  htmlspecialchars(round(str_replace(",", ".", $formFields["ProductPrice"]), 2));
		$quantity			=  htmlspecialchars($formFields["quantity"]);

		$uploaddir	= WWW_PATH.'/images/shop/';
		$uploadfile = $uploaddir.basename($_FILES['userfile']['name']);
		move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
		$img		= htmlspecialchars(basename($_FILES['userfile']['name']));

		try {

			$newItem = new AdminModel();
			$newItem->createItem($ProductName, $ProductDescription, $ProductPrice, $quantity, $img);
			$http->redirectTo("/admin/post");

		} catch (Exception $exception) {

			// Afficher un message d'erreur
			$form = new ItemForm();
			$form->bind($formFields); // Pré-remplissage du formulaire
			$form->setErrorMessage($exception->getMessage());
			return ["_form" => $form];
		}

	}
}

?>