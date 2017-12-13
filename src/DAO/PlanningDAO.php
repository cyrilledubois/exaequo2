<?php
namespace WF3\DAO;

class PlanningDAO extends DAO{

	// Select planning generale
    public function selectPlanning(){
		$result = $this->bdd->query('SELECT * FROM planning ORDER BY datecours asc LIMIT 10');
		return $result->fetchALL(\PDO::FETCH_ASSOC);
    }

	// Select planning de "date à date"
	// PREVOIR UN LIKE EN FIN DE REQUETE
	public function selectPeriod($datecours){
		$result = $this->bdd->prepare('SELECT * FROM planning WHERE datecours = :datecours');
		$result->bindValue(':datecours', $datecours, \PDO::PARAM_INT);
		$result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	public function getInfoPlanning($datejour){
		$result = $this->bdd->prepare ('SELECT * FROM planning INNER JOIN cours ON planning.coursid = cours.id WHERE datecours LIKE :datejour ORDER BY datecours');
        $result->bindValue(':datejour', '%' . $datejour . '%');
        $result->execute();
        return $result->fetchALL(\PDO::FETCH_ASSOC);
    }
	
	// Permet de chercher les cours semblable selon l'id dans le planning 
	public function selectCours($idCours){
		$result = $this->bdd->prepare('SELECT * FROM planning INNER JOIN cours WHERE id = :id');
		$result->bindValue(':id', $idCours, \PDO::PARAM_INT);
		$result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	// 
	public function selectIntensity($intensite){
		$result = $this->bdd->prepare('SELECT * FROM planning INNER JOIN cours ON planning.coursid = cours.id WHERE intensite = :intensite');
		$result->bindValue(':intensite', $intensite, \PDO::PARAM_INT);
		$result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}
	// Trouve la dernière date générée
    public function lastDate(){
    	//Trouve la dernière date générée :
		$result = $this->bdd->query('SELECT MAX(datecours) FROM planning');
		return $result->fetch(\PDO::FETCH_ASSOC);
	}

}
