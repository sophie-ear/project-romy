<?php

class UserSession {
	
	
	/*
	 * Démarrer une session si aucune n'est initialisée
	 */
	public function __construct(){
	
		if(session_status() == PHP_SESSION_NONE){
			session_start();
		}
	}
	
	
	/*
	 * Créer les éléments de la session
	 * @param $id - ID de la personne connectée
	 * @param $firstname - Son prénom
	 * @param $lastname - Son nom
	 * @param $statut - Son statut (admin/client)
	 */
	public function create($id, $firstname, $lastname, $statut){
		
		$_SESSION["id"] 		= $id;
		$_SESSION["firstname"]	= $firstname;
		$_SESSION["lastname"]	= $lastname;
		$_SESSION["statut"] 	= $statut;
	}


	/*
	 * Récupérer l'ID de la personne connectée
	 * @return {int} - Entier
	 */
	public function getID(){
		
		return $_SESSION["id"];
	}


	/*
	 * Récupérer le prénom de la personne connectée
	 * @return {string} - chaîne de caractère
	 */
	public function getName(){
		
		return $_SESSION["firstname"];
	}
	
	
	/*
	 * Vérifier si la personne connectée est un administrateur
	 * @return {bool} - Booléen
	 */
	public function isAdmin(){
		// vérifie si il y a une session en cours et si c'est un administrateur
		if(!empty($_SESSION["id"]) && $_SESSION["statut"] == 3){
			return true;
		}
		return false;
	}
	
	
	/*
	 * Vérifier si une session est en cours
	 * @return {bool} - Booléen
	 */
	public function isLogged(){
		
		if(!empty($_SESSION["id"])){
			return true;
		}
		return false;
	}
	
	
	/*
	 * Vider la session
	 */
	public function destroySession(){
		
		session_destroy();
	}
}   