<?php
namespace WF3\DAO;

class PlanningDAO extends DAO{

	//je crée un attribut qui va contenir un objet de classe UsersDAO (la lcasse qui nous permet de manipuler la table users)
	private $usersDAO;
	//le setter associé
	public function setUsersDAO(UsersDAO $usersDAO){
		$this->usersDAO = $usersDAO;
	}

	// Select planning generale
    public function selectPlanning(){
        $result = $this->bdd->query('SELECT * FROM planning ORDER BY date_cours ASC LIMIT 10');
    }

	// Select planning de "date à date"
	public function selectPeriod(){
		$result = $this->bdd->prepare('SELECT * FROM planning WHERE date_cours = :date_cours');
	}
	
	// Update planning 

	// Supprimer planning du "date à date"

	// 
		
}