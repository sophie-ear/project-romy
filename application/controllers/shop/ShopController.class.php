<?php

class ShopController {

	public function httpGetMethod(Http $http, array $queryFields)
	{
		// APPEL DES PRODUITS DEPUIS LA BDD dans ProductModel
		$productModel = new ProductModel();
		$products = $productModel->listAll(); 
		
		$title		= "RomyArt - Boutique";
		$content	= "RomyArt - En vente des totes bags personnalisés et/ou brodés à la main";

		return ["FlashBag" => new FlashBag(), "products"=>$products, "title" => $title, "content" => $content];
	}

	public function httpPostMethod(Http $http, array $formFields)
	{
		
	}
}