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

	public function getInfoPlanning($date_jour){
		$result = $this->bdd->prepare ('SELECT planning.id, coursid, duree, datecours, nom, intensite, placemax FROM planning INNER JOIN cours ON planning.coursid = cours.id WHERE datecours LIKE :datejour ORDER BY datecours');
        $result->bindValue(':datejour', '%' . $date_jour . '%');
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

	public function CountUserByReserv($idreserv){
		$result = $this->bdd->prepare('SELECT COUNT(usersid) FROM users_has_planning WHERE PlanningidPlanning = :idplanning');
		$result->bindValue(':idplanning', $idreserv, \PDO::PARAM_INT);
		$result->execute();
		$count = $result->fetch(\PDO::FETCH_ASSOC);
		return $count['COUNT(usersid)'];
	}

	public function maxReserv($iduser){
		$result = $this->bdd->prepare('SELECT * FROM users_has_planning WHERE usersid = :idsession');
		$result->bindValue(':idsession', $iduser, \PDO::PARAM_INT);
		$result->execute();
		$data = [];
		$rows = $result->fetchAll(\PDO::FETCH_ASSOC);
		
		foreach($rows as $row){
			$data[$row['PlanningidPlanning'] . '--' . $row['usersid']] = $row;
		}
		return $data;

	}

	//FUNCTION POUR PAS FAIRE PLUSIEURS RESERV
	//public function fullReserv($userhasreserv){
		//$result = $this->bdd->prepare('SELECT * FROM users_has_planning WHERE usersid, PlanningidPlanning = :usersid, :PlanningidPlanning ');
		//$result->bindValue(':usersid', $userhasreserv, \PDO::PARAM_INT);
		//$result->bindValue(':PlanningidPlanning', $userhasreserv, \PDO::PARAM_INT);
		//$result->execute();
		//return $result->fetch(\PDO::FETCH_ASSOC);
	//}
}
