<?php
namespace WF3\DAO;

class PlanningDAO extends DAO{

	// Select planning generale
    public function selectPlanning($date_cours){
		$result = $this->bdd->query('SELECT * FROM planning ORDER BY date_cours asc LIMIT 10');
		return $result->fetchALL(\PDO::FETCH_ASSOC);
    }

	// Select planning de "date à date"
	// PREVOIR UN LIKE EN FIN DE REQUETE
	public function selectPeriod($date_cours){
		$result = $this->bdd->prepare('SELECT * FROM planning WHERE date_cours = :date_cours');
		$result->bindValue(':date_cours', $date_cours, \PDO::PARAM_INT);
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
		$result = $this->bdd->prepare('SELECT * FROM planning INNER JOIN cours ON planning.cours_id = cours.id WHERE intensite = :intensite');
		$result->bindValue(':intensite', $intensite, \PDO::PARAM_INT);
		$result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	//Modifier le planning
	public function modifPlanning($idPlanning){
		$result = $this->bdd->prepare('UPDATE planning 
		SET date_cours = :date_cours, duree = :duree, intensite = :intensite, place_max = :placeMax, decouverteMax = :decouverteMax, cours_id = :coursId 
		WHERE idPlanning = :idPlanning')
		$result->bindValue(':date_cours', $date_cours, \PDO::PARAM_INT);
		$result->bindValue(':duree', $duree, \PDO::PARAM_INT);
		$result->bindValue(':intensite', $intensite, \PDO::PARAM_INT);
		$result->bindValue(':placeMax', $placeMax, \PDO::PARAM_INT);
		$result->bindValue(':decouverteMax', $decouverteMax, \PDO::PARAM_INT);
		$result->bindValue(':coursId', $coursId, \PDO::PARAM_INT);
		$result->bindValue(':idPlanning', $idPlanning, \PDO::PARAM_INT);
		$result->execute();
	} 

	

	// Supprimer planning du "date à date"

	// 
		
}