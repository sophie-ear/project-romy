<?php

class UserController
{

	public function httpGetMethod(Http $http, array $queryFields)
	{
		$title = "RomyArt - Inscription";
		
		return ["_form" => new UserForm(), "title" => $title];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
		// Récupération des données du formulaire
		$firstName	= htmlspecialchars($formFields["firstName"]);
		$lastName	= htmlspecialchars($formFields["lastName"]);
		$day		= htmlspecialchars($formFields["day"]);
		$month		= htmlspecialchars($formFields["month"]);
		$year		= htmlspecialchars($formFields["year"]);
		$address	= htmlspecialchars($formFields["address"]);
		$zipcode	= htmlspecialchars($formFields["zipcode"]);
		$city		= htmlspecialchars($formFields["city"]);
		$country	= htmlspecialchars($formFields["country"]);
		$phone		= htmlspecialchars($formFields["phone"]);
		$email		= strtolower(htmlspecialchars($formFields["email"]));
		$password	= htmlspecialchars($formFields["password"]);

		$naissance	= $year."-".$month."-".$day;
		try {

			//Si l'email est déjà présent, on renvoit les mêmes variables pour que l'user n'ait pas besoin de tout réécrire
			$userModel = new UserModel();
			$userModel->createUser($firstName, $lastName, $naissance, $address, $zipcode, $city, $country, $phone, $email, $password);
			$http->redirectTo("/");
			
		} catch (Exception $exception) {

			// Afficher un message d'erreur
			$form = new UserForm();
			$form->bind($formFields); // Pré-remplissage du formulaire
			$form->setErrorMessage($exception->getMessage());
			return ["_form" => $form];
		}
	}
}
