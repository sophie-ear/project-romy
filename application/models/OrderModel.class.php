<?php

class OrderModel{


	/*
	 * Changement du statut d'une commande
	 * @param $id - ID de la commande concernée
	 * @param $status - Nouveau statut
	 */
	public function changeOrderStatus($id, $status) { 
		
		$update = new Database();
		$update->executeSql('UPDATE orders SET OrderStatus = ? WHERE OrderID = ?', array($status, $id));

		$flashBag = new FlashBag();
		$flashBag->add("Le statut de la commande $id a bien été mis à jour.");	
	}


	/*
	 * Créer une nouvelle commande
	 * @param $userID - ID de l'user qui passe commande
	 * @param $ttc - Prix TTC
	 * @param $ht - Prix HT
	 * @param $products - Produits commandés
	 */
	public function createOrder($userID, $ttc, $ht, $products) {

		$db_order = new Database();
		$order = "INSERT INTO orders (UserID, OrderDate, OrderHT, OrderTTC) VALUES (?, NOW(), ?, ?)";
		$order_id = $db_order->executeSql($order, [$userID, $ht, $ttc]); 
		
		
		foreach($products as $product) {

			// INSERTION DU DETAIL DE LA COMMANDE
			$detail = "INSERT INTO details VALUES (NULL, ?, ?, ?)";
			$db_order->executeSql($detail, [$order_id, $product["ProductID"], $product["quantity"]]);

			// MAJ du stock
			$this->updateQty($product["ProductID"], $product["quantity"]);
		}
		
		$flashBag = new FlashBag();
		$flashBag->add("Votre commande a bien été validée.");
		
		//On vide le panier après avoir envoyé la commande
		$basketModel = new BasketSession();
		$basketModel->destroyBasket();
		
	}


	/*
	 * Récupérer les statuts des commandes
	 * @return {array} - Tableau de statut des commandes
	 */
	public function getOrderStatus() {

		$status = new Database();
		$sql = 'SELECT 	OrderStatus, StatusName FROM orderstatus';
		return $status->query($sql, array());
	}


	/*
	 * Récupérer le détail d'une commande
	 * @param $orderID - ID de la commande
	 * @return {array} - Tableau du détail de la commande
	 */
	public function listDetails($orderID) {

		$details = new Database();

		$sql = 'SELECT 	details.DetailsID,
						details.OrderID, 
						details.ProductID, 
						details.DetailsQty,
						product.ProductID, 
						product.ProductName, 
						product.ProductPrice,
						orders.OrderHT,
						orders.OrderTTC
				FROM details
				INNER JOIN product ON product.ProductID = details.ProductID
				INNER JOIN orders ON orders.OrderID = details.OrderID
				WHERE details.OrderID = ?';

		return $details->query($sql, array($orderID));
	}


	/*
	 * Récupèrer toutes les commandes
	 * @return {array} - Tableau de toutes les commandes
	 */
	public function listOrders() {

		$orders = new Database();

		$sql = 'SELECT 	ord.OrderID,
						ord.UserID,
						ord.OrderDate,
						ord.OrderHT,
						ord.OrderTTC,
						ord.OrderStatus,
						user.UserID,
						user.UserFirstname,
						user.UserLastname,
						ordSta.OrderStatus,
						ordSta.StatusName
				FROM orders ord
				INNER JOIN user ON ord.UserID = user.UserID
				INNER JOIN orderstatus ordSta ON ord.OrderStatus = ordSta.OrderStatus
				ORDER BY ord.OrderID';

		return $orders->query($sql, array());
	}


	/*
	 * Récupèrer toutes les commandes d'un user
	 * @param $id - ID de l'user concerné
	 * @return {array} - Tableau de toutes les commandes de cet user
	 */
	public function listUserOrders($id) {
		$orders = new Database();

		$sql = 'SELECT 	ord.OrderID,
						ord.UserID,
						ord.OrderDate,
						ord.OrderHT,
						ord.OrderTTC,
						ord.OrderStatus,
						ordSta.OrderStatus,
						ordSta.StatusName
				FROM orders ord
				INNER JOIN orderstatus ordSta ON ord.OrderStatus = ordSta.OrderStatus
				WHERE ord.UserID = ?';

		return $orders->query($sql, array($id));
	}


	/*
	 * Mettre à jour le stock
	 * @param $id - ID du produit
	 * $qty - Quantité à déduire du stock
	 */
	private function updateQty($id, $qty) {

		$update = new Database();
		$update->executeSql('UPDATE product 
							SET 	ProductQty = (ProductQty - ?)
							WHERE	ProductID = ?', array($qty, $id));
	}
	
}

?>