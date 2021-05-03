<?php

class AdminModel {


	/*
	 * Changer le statut d'un utilisateur
	 * @param $id - ID de l'user concerné
	 * @param $status - Nouveau statut
	 */
	public function changeUserStatus($id, $status) {

		$userModel	= new UserModel();
		$user		= $userModel->listOneUser($id);
		$firstname	= $user["UserFirstname"];
		$lastname	= $user["UserLastname"];
		
		$update = new Database();
		$update->executeSql('UPDATE user SET UserStatus = ? WHERE UserID = ?', array($status, $id));

		$flashBag = new FlashBag();
		$flashBag->add("Le statut de $firstname $lastname a été mis à jour.");	
	}


	/*
	 * Créer un nouvel article pour le Shop
	 * @param $name - Nom du nouveau produit
	 * @param $description - Description
	 * @param $price - Prix
	 * @param $qty - Quantité en stock
	 * @param $image - Nom de l'image
	 */
	public function createItem($name, $description, $price, $qty, $image) {

		$new_product = new Database();

		// RegEx pour vérifier si c'est un float
		if(!preg_match("#\d{0,3}[,\\.]?(\\d{1,2})?#", $price) || $price == 0){
			throw new Exception("Le prix doit être un nombre.");
		}

		// RegEx pour vérifier si c'est un int
		if(!preg_match("#^[0-9]{0,}$#", $qty)){
			throw new Exception("La quantité doit être un nombre entier.");
		}

		// Vérification du nom de l'image
		$productModel	= new ProductModel();
		$products		= $productModel->listAll();
		
		foreach($products as $product)
		{
			if($product["ProductImage"] == $image)
			{
				throw new Exception("Une image avec ce nom existe déjà.");
			}
		}
		

		$sql = "INSERT INTO product (ProductName, 
									ProductDescription, 
									ProductImage, 
									ProductPrice, 
									ProductQty)
						VALUES (?, ?, ?, ?, ?)";
		
		$new_product->executeSql($sql, [$name, $description, $image, $price, $qty]);

		$flashBag = new FlashBag();
		$flashBag->add("Votre $name a bien été ajouté.");		
	}


	/*
	 * Supprimer un article du Shop
	 * @param $id - ID de l'article à supprimer
	 */
	public function deleteItem($id) {

		// Récupérer le nom de son image pour le supprimer du dossier img/shop
		$productModel	= new ProductModel();
		$product		= $productModel->getProductInfo($id);
		$image			= $product["ProductImage"];

		// Suppression de l'image correspondante
		unlink(WWW_PATH."/images/shop/".$image);

		$delete = new Database();
		$delete->executeSql("DELETE FROM product WHERE ProductID = ?", array($id));

		$flashBag = new FlashBag();
		$flashBag->add("Votre article a bien été supprimé.");
	}


	/*
	 * Supprimer un user
	 * @param $id - ID de l'user à supprimer
	 */
	public function deleteUser($id) { 

		$db = new Database();
		
		$user = $db->queryOne("SELECT UserStatus FROM user WHERE UserID = ?", array($id));
		
		// Si c'est un admin on ne peut pas supprimer le compte
		if($user["UserStatus"] == 3){
			
			$flashBag = new FlashBag();
			$flashBag->add("Vous ne pouvez pas supprimer un compte administrateur.");
		}
		else{
			
			$db->executeSql("DELETE FROM user WHERE UserID = ?", array($id));

			$flashBag = new FlashBag();
			$flashBag->add("Le compte a été supprimé.");
		}
	}


	/*
	 * Modification des données d'un user (sauf mdp)
	 * @param $id - ID de l'user à modifier
	 * @param $firstname - Prénom
	 * @param $lastname - Nom
	 * @param $birth - Date de naissance
	 * @param $address - Adresse
	 * @param $zipcode - Code postal
	 * @param $city - Ville
	 * @param $country - Pays
	 * @param $phone - Téléphone
	 * @param $email - Email
	 */
	public function modifyUser($id, $firstname, $lastname, $birth, $address, $zipcode, $city, $country, $phone, $email) {

		$database = new Database();

		// Vérification de l'email
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			throw new Exception("Cette adresse '$email' est invalide.");
		}

		// RegEx pour vérifier un code postal à 5 chiffres
		if(!preg_match("#^[\d]{5}$#", $zipcode))
		{
			throw new Exception("Un code postal doit contenir 5 chiffres.");
		}

		// RegEx qui vérifie que le numéro est composé de 10 chiffres commençant par 0, en supprimant tous les - . et espaces que l'user pourrait potientiellement ajouter
		if(!preg_match("#^0[\d]([-. ]?[\d]{2}){4}$#", $phone))
		{
			throw new Exception("Un numéro de téléphone doit contenir 10 chiffres.");
		}

		$sql = "UPDATE 	user
				SET		UserFirstname = ?, 
						UserLastname = ?, 
						UserBirth = ?, 
						UserAddress = ?, 
						UserZipcode = ?, 
						UserCity = ?, 
						UserCountry = ?, 
						UserPhone = ?,
						UserMail = ?
				WHERE 	UserID = ?";
		$database->executeSql($sql, [$firstname, $lastname, $birth, $address, $zipcode, $city, $country, $phone, $email, $id]);

		$flashBag = new FlashBag();
		$flashBag->add("Les informations de $firstname $lastname ont bien été modifiées.");
	}


	/*
	 * Modifier un article du shop
	 * @param $id - ID de l'article concerné
	 * @param $name - Nom du produit
	 * @param $description - Description
	 * @param $price - Prix
	 * @param $qty - Quantité en stock
	 * @param $image - Nom de l'image
	 */
	public function updateItem($id, $name, $description, $price, $qty, $image) {

		$update = new Database();
		
		// RegEx pour vérifier si c'est un float
		if(!preg_match("#\d{0,3}[,\\.]?(\\d{1,2})?#", $price) || $price == 0){
			throw new Exception("Le prix doit être un nombre > 0.");
		}
		
		// RegEx pour vérifier si c'est un int
		if(!preg_match("#^[0-9]{0,}$#", $qty)){
			throw new Exception("La quantité doit être un nombre entier.");
		}

		// Si on ne change pas l'image, on récupère son nom dans la BDD pour ne pas renvoyer un string vide, sinon on vérifie que le nom n'existe pas déjà
		$productModel = new ProductModel();
		$product = $productModel->getProductInfo($id);
		
		if(empty($image))
		{
			$image = $product["ProductImage"];
		}
		else
		{
			$products = $productModel->listAll();
			foreach($products as $product)
			{
				if($product["ProductImage"] == $image)
				{
					throw new Exception("Une image avec ce nom existe déjà.");
				}
			}
			
			// Suppression de l'ancienne image
			unlink(WWW_PATH."/images/shop/".$product["ProductImage"]);
		}

		$update->executeSql("UPDATE	product
							SET 	ProductName = ?,
									ProductDescription = ?,
									ProductImage = ?,
									ProductPrice = ?,
									ProductQty = ?
							WHERE	ProductID = ?",
				array($name, $description, $image, $price, $qty, $id));
		
		$flashBag = new FlashBag();
		$flashBag->add("$name a bien été modifié.");
	}

}