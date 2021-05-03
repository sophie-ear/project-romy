<?php

class BasketSession {

	
	/*
	 * Démarrer une session si aucune n'est initialisée
	 */
	public function __construct(){ 

		if (session_status() == PHP_SESSION_NONE){
			session_start();
		}
	}


	/*
	 * Récupèrer le TTC du panier
	 * @return {float} - TTC du panier
	 */
	public function getTTC(){

		$ttc = 0;
		foreach ($_SESSION["basket"] as $product){
			$ttc += $product["ProductPrice"] * $product["quantity"];
		}
		return $ttc;
	}


	/*
	 * Récupèrer les produits du panier s'il n'est pas vide
	 * @return {array} - Tableau des produits existants ou vide
	 */
	public function getProducts(){

		if (!array_key_exists("basket", $_SESSION)){ 
			return [];
		}
		return $_SESSION["basket"];
	}


	/*
	 * Ajouter un produit au panier
	 * @param $product_id - ID du produit ajouté au panier
	 * @param $qty - Quantité du produit
	 * @param $qtyMax - Quantité maximale disponible
	 * @param $name - Nom du produit
	 * @param $price - Prix du produit
	 * @param $image - Image du produit
	 */
	public function addProduct($product_id, $qty, $qtyMax, $name, $price, $image){ 

		$session = new UserSession();
		if ($session->isLogged() == true){

			// MESSAGE LORS AJOUT ARTICLE
			$flashBag = new FlashBag();
			$flashBag->add("<b>$name</b> a bien été ajouté");

			// AJOUT D'UN ARTICLE EXISTANT DANS LE PANIER
			$exist = false;
			if (array_key_exists("basket", $_SESSION)){

				foreach ($_SESSION["basket"] as $key => $element){
					
					//si l'ID du produit existe déjà on change juste la quantité
					if ($element["ProductID"] == $product_id){ 

						$exist = true;
						$_SESSION["basket"][$key]["quantity"] += $qty;
					}
				}
			}
			
			// AJOUT DE L'ARTICLE DANS LE PANIER 1ERE FOIS
			if ($exist == false){ 

				$_SESSION["basket"][] = ["ProductID"	=> $product_id, 
										"quantity"		=> $qty, 
										"quantityMax"	=> $qtyMax, 
										"ProductName"	=> $name, 
										"ProductPrice"	=> $price, 
										"ProductImage"	=> $image];
			}
		}
	}

	/*
	 * Vérifier la quantité maximale disponible
	 * @param $productID - ID du produit sélectionné
	 * @param $qtySelected - Quantité sélectionnée
	 * @return {bool} - Booléen
	 */
	public function atMax($productID, $qtySelected){ 

		if (array_key_exists("basket", $_SESSION)){

			foreach($_SESSION["basket"] as $key => $element){
				
				// si le produit existe déjà dans le panier
				if($element["ProductID"] == $productID){ 

					$qty = $_SESSION["basket"][$key]["quantity"] + $qtySelected;
					
					//si la quantité sélectionnée totale > quantité max dispo
					if($qty > $_SESSION["basket"][$key]["quantityMax"]){

						return true;
					}
				}
			}
		}
		else{
			
			return false;
		}
	}

	
	/*
	 * Modifier la quantité d'un produit dans le panier
	 * @param $id - ID du produit concerné
	 * @param $qty - Nouvelle quantité
	 */
	public function modifyQty($id, $qty){ 

		foreach ($_SESSION["basket"] as $key => $element){
			if($element["ProductID"] == $id){
				$_SESSION["basket"][$key]["quantity"] = $qty;
			}
		}
	}


	/*
	 * Supprimer un article du panier
	 * @param $id - ID du produit à supprimer
	 */
	public function removeProduct($id){

		foreach ($_SESSION["basket"] as $key => $element){
			if ($element["ProductID"] == $id){
				unset($_SESSION["basket"][$key]);
			}
		}
	}


	/*
	 * Vider le panier
	 */
	public function destroyBasket(){

		unset($_SESSION["basket"]);
	}
}
