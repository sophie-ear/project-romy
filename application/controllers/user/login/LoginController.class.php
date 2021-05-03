<?php

class LoginController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		$title = "RomyArt - Page de connexion";
		
		return ["_form"=>new LoginForm(), "title" => $title];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
		// Récupération des données du formulaire
		$email      = strtolower(htmlspecialchars($formFields["email"]));
		$password   = htmlspecialchars($formFields["password"]);

		try{

			$userModel = new UserModel();
			$datas = $userModel->verifyLogin($email, $password); 

			// Envoi des données à $_SESSION
			$userSession = new UserSession();
			$userSession->create($datas['UserID'], $datas['UserFirstname'], $datas['UserLastname'], $datas['UserStatus']);

			$http->redirectTo("/");

		}catch(Exception $exception){

			// Afficher un message d'erreur
			$form = new LoginForm();
			$form->bind($formFields); // Pré-remplissage du formulaire
			$form->setErrorMessage($exception->getMessage());
			return ["_form" => $form];
		}
	}
}

?>