<?php

class ModifyController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		// RETOUR ACCUEIL POUR NON ADMIN
		$session = new UserSession();

		if($session->isAdmin() === false){

			$flashBag = new FlashBag();
			$flashBag->add("Veuillez vous connecter avec un compte administrateur pour accéder à cette page.");
			$http->redirectTo("/");
		}

		// Récupération de l'ID de l'utilisateur qu'on va update
		$userID = $queryFields["ID"];

		// RECUPERATION DES INFOS DE CET UTILISATEUR
		$userModel = new UserModel();
		$user = $userModel->listOneUser($userID);
		
		$title = "Modification de ".$user['UserFirstname']." ".$user['UserLastname'];

		return ["_form" => new UserForm(), "user" => $user, "title" => $title];
	}


	public function httpPostMethod(Http $http, array $formFields)
	{
		// Récupération des données du formulaire
		$id			= $formFields["UserID"];
		$firstName	= htmlspecialchars($formFields["firstname"]);
		$lastName	= htmlspecialchars($formFields["lastname"]);
		$year		= htmlspecialchars($formFields["year"]);
		$month 		= htmlspecialchars($formFields["month"]);
		$day		= htmlspecialchars($formFields["day"]);
		$address	= htmlspecialchars($formFields["address"]);
		$zipcode	= htmlspecialchars($formFields["zipcode"]);
		$city		= htmlspecialchars($formFields["city"]);
		$country	= htmlspecialchars($formFields["country"]);
		$phone		= htmlspecialchars($formFields["phone"]);
		$email 		= strtolower(htmlspecialchars($formFields["email"]));

		$naissance = $year."-".$month."-".$day;

		try {
			//Si l'email est déjà présent, on renvoit les mêmes variables pour que l'user n'ait pas besoin de tout réécrire
			$adminModel = new AdminModel();
			$adminModel->modifyUser($id, $firstName, $lastName, $naissance, $address, $zipcode, $city, $country, $phone, $email);
			$http->redirectTo("/admin/listusers");

		} catch (Exception $exception) {

			//Afficher un message d'erreur
			$form = new UserForm();
			//$form->bind($formFields); // Pré-remplissage du formulaire
			$form->setErrorMessage($exception->getMessage());

			$user = array(
				"UserID" 		=> $id,
				"UserFirstname"	=> $firstName,
				"UserLastname" 	=> $lastName,
				"day" 			=> $day,
				"month" 		=> $month,
				"year" 			=> $year,
				"UserAddress" 	=> $address,
				"UserZipcode" 	=> $zipcode,
				"UserCity" 		=> $city,
				"UserCountry" 	=> $country,
				"UserPhone" 	=> $phone,
				"UserMail" 		=> $email,
			);

			return ["_form" => $form, "user"=>$user];
		}

	}
}

?>