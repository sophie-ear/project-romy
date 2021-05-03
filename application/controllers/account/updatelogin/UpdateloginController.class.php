<?php

class UpdateloginController {

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
		
		$title = "Romy Art - Modification du login";

		return ["_form" => new UserForm(), "user" => $user, "title" => $title];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
		$userSession = new UserSession();
		
		// Récupération des données du formulaire
		$id 			= $userSession->getID();
		$email			= strtolower(htmlspecialchars($formFields["email"]));
		$oldPassword	= htmlspecialchars($formFields["oldPassword"]);
		$newPassword	= htmlspecialchars($formFields["newPassword"]);
		$confirmPassword= htmlspecialchars($formFields["confirmPassword"]);

		try {

			//Si l'email est déjà présent, on renvoit les mêmes variables pour que l'user n'ait pas besoin de tout réécrire
			$userModel = new UserModel();
			$userModel->updateLogin($id, $email, $oldPassword, $newPassword, $confirmPassword);
			$http->redirectTo("/account");

		} catch (Exception $exception) {

			// Afficher un message d'erreur
			$form = new UserForm();
			$form->bind($formFields); // Pré-remplissage du formulaire
			$form->setErrorMessage($exception->getMessage());

			// RECUPERATION DES INFOS DE CET UTILISATEUR pour le renvoyer dans le bind
			$userModel = new UserModel();
			$user = $userModel->listOneUser($id);

			return ["_form" => $form, "user" => $user];
		}
	}
}