<?php

class LogoutController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		// On détruit la session lors de la déconnexion
		$userSession = new UserSession();
		$userSession->destroySession();
		
		// Message de déconnexion et retour sur la page d'accueil
		$flashBag = new FlashBag();
		$flashBag->add("Vous êtes bien déconnecté.");
		$http->redirectTo("");
		
	}
}

?>