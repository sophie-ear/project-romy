<?php

class UpdateuserController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		// VERIFICATION CONNEXION
		$session = new UserSession();
		if($session->isLogged() === false){
			
			// Redirection vers la page de connexion
			$http->redirectTo("/user/login");
		}

		$userID = $session->getID();
		
		// RECUPERATION DES INFOS DE CET UTILISATEUR
		$userModel = new UserModel();
		$user = $userModel->listOneUser($userID);
		
		$title = "Romy Art - Modifications des infos";

		return ["_form" => new UserForm(), "user" => $user, "title" => $title];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
		$userSession = new UserSession();
		// Récupération des données du formulaire
		$id 		= $userSession->getID();
		$firstname	= htmlspecialchars($formFields["firstName"]);
		$lastname	= htmlspecialchars($formFields["lastName"]);
		$year 		= htmlspecialchars($formFields["year"]);
		$month 		= htmlspecialchars($formFields["month"]);
		$day		= htmlspecialchars($formFields["day"]);
		$address	= htmlspecialchars($formFields["address"]);
		$zipcode	= htmlspecialchars($formFields["zipcode"]);
		$city		= htmlspecialchars($formFields["city"]);
		$country	= htmlspecialchars($formFields["country"]);
		$phone		= htmlspecialchars($formFields["phone"]);

		$naissance	=  $year."-".$month."-".$day;

		try {

			$userModel = new UserModel();
			$userModel->updateUser($id, $firstname, $lastname, $naissance, $address, $zipcode, $city, $country, $phone);
			$http->redirectTo("/account");

		} catch (Exception $exception) {

			// Afficher un message d'erreur
			$form = new UserForm();
			$form->bind($formFields); // Pré-remplissage du formulaire
			$form->setErrorMessage($exception->getMessage());

			// Récupération des infos pour le bind
			$user = array(
				"UserID" 		=> $id,
				"UserFirstname"	=> $firstname,
				"UserLastname" 	=> $lastname,
				"day" 			=> $day,
				"month" 		=> $month,
				"year" 			=> $year,
				"UserAddress" 	=> $address,
				"UserZipcode" 	=> $zipcode,
				"UserCity" 		=> $city,
				"UserCountry" 	=> $country,
				"UserPhone" 	=> $phone,
			);

			return ["_form" => $form, "user" => $user];
		}
	}
}