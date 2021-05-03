<?php

class ProductModel {


	/*
	 * Récupèrer toutes les infos d'un produit
	 * @param $id - ID du produit concerné
	 * @return {array} - Tableau des infos de ce produit
	 */
	public function getProductInfo($id) {
		
		$database = new Database();
		$sql = 'SELECT	ProductID, 
						ProductName, 
						ProductDescription, 
						ProductImage, 
						ProductPrice, 
						ProductQty
				FROM	product 
				WHERE	ProductID = ?';

		return $database->queryONE($sql, array($id));;
	}


	/*
	 * Récupèrer tous les produits
	 * @return {array} - Tableau de tous les produits
	 */
	public function listAll() {

		$database = new Database();
		$products = $database->query('SELECT 
										ProductID,
										ProductName,
										ProductDescription,
										ProductImage,
										ProductPrice,
										ProductQty
								FROM	product');
		return $products;
	}

}
