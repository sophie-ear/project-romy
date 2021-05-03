<?php

class UserModel {


	/*
	 * Création d'un nouvel utilisateur
	 * @param $firstname - Prénom
	 * @param $lastname - Nom
	 * @param $birth - Date de naissance
	 * @param $address - Adresse
	 * @param $zipcode - Code postal
	 * @param $city - Ville
	 * @param $country - Pays
	 * @param $phone - Téléphone
	 * @param $email - Email
	 * @param $password - Mot de passe
	 */
	public function createUser($firstname, $lastname, $birth, $address, $zipcode, $city, $country, $phone, $email, $password) {

		$database = new Database();

		// Vérification de l'email
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			throw new Exception("Votre adresse '$email' est invalide.");
		}

		// Vérification de l'existance de l'email dans la BDD
		$user = $database->queryOne("	SELECT UserID 
										FROM user 
										WHERE UserMail = ?", 
										array($email));

		if(!empty($user))
		{
			throw new Exception("Votre adresse '$email' est déjà enregistrée.");
		}

		// Vérification de la longueur du mdp
		while(strlen($password) < 8)
		{
			throw new Exception("Votre mot de passe doit contenir 8 caractères minimum.");
		}

		// Envoi du mdp au cryptage
		$password = $this->hashPassword($password);
		
		// RegEx pour vérifier que le code postal contient 5 chiffres
		if(!preg_match("#^[\d]{5}$#", $zipcode))
		{
			throw new Exception("Votre code postal doit contenir 5 chiffres.");
		}

		// Regular Expression qui vérifie que le numéro est composé de 10 chiffres commençant par 0, en supprimant tous les - . et espaces que l'user pourrait potientiellement ajouter
		if(!preg_match("#^0[\d]([-. ]?[\d]{2}){4}$#", $phone))
		{
			throw new Exception("Votre numéro de téléphone doit contenir 10 chiffres.");
		}
		
		$phone = str_replace(["-","."," "], "", $phone);

		// Insertion des infos du nouvel utilisateur dans la BDD
		$sql = "INSERT INTO user (UserFirstname, 
								UserLastname, 
								UserBirth, 
								UserAddress, 
								UserZipcode, 
								UserCity, 
								UserCountry, 
								UserPhone, 
								UserMail, 
								UserPassword,
								AccountDate) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
		$database->executeSql($sql, [$firstname, $lastname, $birth, $address, $zipcode, $city, $country, $phone, $email, $password]);

		$flashBag = new FlashBag();
		$flashBag->add("Votre compte a bien été enregistré.");
	}


	/*
	 * Récupérer les statuts des users
	 * @return {array} - Tableau des statuts
	 */
	public function getUserStatus() {

		$status = new Database();
		$sql	= 'SELECT UserStatus, StatusName FROM userstatus';
		return $status->query($sql, array());
	}


	/*
	 * Cryptage du mot de passe
	 * @param $password - Mot de passe clear
	 * @return {string} - Mot de passe crypté
	 */
	private function hashPassword($password) {

		$salt = '$2y$11$'.substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 22);
		return crypt($password, $salt); 
	}


	/*
	 * Récupérer les infos d'un user
	 * @param $id - ID de l'user concerné
	 * @return {array} - Tableau des infos du user
	 */
	public function listOneUser($id) {

		$user = new Database();
		$sql = 'SELECT	user.UserID,
						user.UserFirstname,
						user.UserLastname,
						DAY(user.UserBirth) AS day,
						MONTH(user.UserBirth) AS month,
						YEAR(user.UserBirth) AS year,
						user.UserAddress,
						user.UserZipcode,
						user.UserCity,
						user.UserCountry,
						user.UserPhone,
						user.UserMail,
						user.UserPassword,
						user.AccountDate,
						useSta.UserStatus,
						useSta.StatusName
				FROM user
				INNER JOIN userstatus useSta ON user.UserStatus = useSta.UserStatus
				WHERE UserID = ?';
		return $user->queryOne($sql, array($id));
	}


	/*
	 * Récupératio de tous les users et leurs infos
	 * @return {array} - Tableau de tous les users et de leurs infos
	 */
	public function listUsers() {

		$users = new Database();

		$sql = 'SELECT	user.UserID,
						user.UserFirstname,
						user.UserLastname,
						user.UserBirth,
						user.UserAddress,
						user.UserZipcode,
						user.UserCountry,
						user.UserPhone,
						user.UserMail,
						user.UserStatus,
						user.AccountDate,
						useSta.UserStatus,
						useSta.StatusName
				FROM user
				INNER JOIN userstatus useSta ON user.UserStatus = useSta.UserStatus
				ORDER BY AccountDate, UserLastname, UserFirstname';
		return $users->query($sql, array());
	}


	/*
	 * Modification des infos de connexion
	 * @param $id - ID de l'user concerné
	 * @param $email - Email
	 * @param $oldPassword - Ancien mot de passe enregistré
	 * @param $newPassword - Nouveau mot de passe
	 * @param $confirmPassword - Nouveau mot de passe bis
	 */
	public function updateLogin($id, $email, $oldPassword, $newPassword, $confirmPassword) {

		$database	= new Database();
		$rqt		= "SELECT 	UserMail, UserPassword FROM user WHERE UserID = ?";
		$user		= $database->queryOne($rqt, array($id)); 

		// Validation de l'email si elle a été changée
		if($user["UserMail"] != $email)
		{
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				throw new Exception("Votre adresse '$email' est invalide.");
			}
		}

		// Vérification du mot de passe avec celui déjà enregistré avant de modifier les données
		if($this->verifyPassword($oldPassword, $user['UserPassword']) == false)
		{
			throw new Exception("Votre mot de passe actuel est incorrect.");
		}

		// Vérification de la longueur du mdp
		while(strlen($newPassword) < 8)
		{
			throw new Exception("Votre nouveau mot de passe doit contenir 8 caractères minimum.");
		}

		while($newPassword != $confirmPassword)
		{
			throw new Exception("Vos nouveaux mots de passe ne sont pas identiques.");
		}

		// Envoi du mdp au cryptage
		$newPassword = $this->hashPassword($newPassword);

		// Modification des login de l'utilisateur
		$sql = "UPDATE 	user
				SET		UserMail = ?,
						UserPassword = ?
				WHERE 	UserID = ?";
		$database->executeSql($sql, [$email, $newPassword, $id]);

		// Message de modification de login sur la page account
		$flashBag = new FlashBag();
		$flashBag->add("Vos informations de connexion ont bien été modifiées.");
	}


	/*
	 * Modification des infos d'un user (sauf email et mdp)
	 * @param $id - ID de l'user
	 * @param $firstname - Prénom
	 * @param $lastname - Nom
	 * @param $birth - Date de naissance
	 * @param $address - Adresse
	 * @param $zipcode - Code postal
	 * @param $city - Ville
	 * @param $country - Pays
	 * @param $phone - Téléphone
	 */
	public function updateUser($id, $firstname, $lastname, $birth, $address, $zipcode, $city, $country, $phone) {

		$database = new Database();

		// RegEx pour vérifier que le code postal contient 5 chiffres
		if(!preg_match("#^[\d]{5}$#", $zipcode))
		{
			throw new Exception("Votre code postal doit contenir 5 chiffres.");
		}

		// RegEx pour vérifier le numéro de téléphone (10 chiffres)
		if(!preg_match("#^0[\d]([-. ]?[\d]{2}){4}$#", $phone))
		{
			throw new Exception("Votre numéro de téléphone doit contenir 10 chiffres.");
		}

		// Modification des infos de l'utilisateur dans la BDD
		$sql = "UPDATE 	user
				SET		UserFirstname = ?, 
						UserLastname = ?, 
						UserBirth = ?, 
						UserAddress = ?, 
						UserZipcode = ?, 
						UserCity = ?, 
						UserCountry = ?, 
						UserPhone = ?
				WHERE 	UserID = ?";
		$database->executeSql($sql, [$firstname, $lastname, $birth, $address, $zipcode, $city, $country, $phone, $id]);

		// Message de modification d'infos sur la page account
		$flashBag = new FlashBag();
		$flashBag->add("Vos informations ont bien été modifiées.");
	}


	/*
	 * Vérification de connexion
	 * @param $email - Email
	 * @param $passwordClear - Mot de passe non crypté
	 * @return {array} - Tableau d'infos sur l'user qui s'est connecté 
	 */
	public function verifyLogin($email, $passwordClear) {

		// Récupération de l'utilisateur ayant cet email
		$dataMail = new Database();
		$rqt = "SELECT	UserID, 
						UserFirstname, 
						UserLastname, 
						UserBirth, 
						UserAddress, 
						UserZipcode, 
						UserCity, 
						UserCountry,
						UserPhone,
						UserMail,
						UserPassword,
						UserStatus 
				FROM 	user 
				WHERE 	UserMail = ?";
		$user = $dataMail->queryOne($rqt, array($email)); 

		// Si l'utilisateur n'existe pas
		if(empty($user) == true)
		{
			throw new Exception("Votre adresse email n'est pas enregistrée"); 
		}

		// Vérification du mot de passe avec celui déjà enregistré
		if($this->verifyPassword($passwordClear, $user['UserPassword']) == false)
		{
			throw new Exception("Votre mot de passe est incorrect.");
		}

		return $user;
	}


	/*
	 * Vérification du mot de passe
	 * @param $password - Mot de passe non crypté
	 * @param $hashedPassword - Mot de passe crypté
	 * @return {bool} - Booléen
	 */
	private function verifyPassword($password, $hashedPassword) {
		
		return crypt($password, $hashedPassword) == $hashedPassword;
	}

}

?>