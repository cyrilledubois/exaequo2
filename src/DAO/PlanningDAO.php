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

	public function getInfoPlanning($date_jour){
        $result = $this->bdd->prepare('SELECT idPlanning, date_cours, duree, nom, intensite FROM planning INNER JOIN cours ON planning.id_cours = cours.id WHERE date_cours LIKE :date_jour');
        $result->bindValue(':date_jour', '%' . $date_jour . '%');
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
		$result = $this->bdd->prepare('SELECT * FROM planning INNER JOIN cours ON planning.id_cours = cours.id WHERE intensite = :intensite');
		$result->bindValue(':intensite', $intensite, \PDO::PARAM_INT);
		$result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	/*
	//Modifier le planning
	public function modifPlanning($idPlanning, $data){
		$result = $this->bdd->prepare('UPDATE planning 
		SET date_cours = :date_cours, duree = :duree, intensite = :intensite, place_max = :placeMax, decouverteMax = :decouverteMax, cours_id = :coursId 
		WHERE idPlanning = :idPlanning')
		$result->bindValue(':date_cours', $data['date_cours']);
		$result->bindValue(':duree', $data['duree']);
		$result->bindValue(':intensite', $data['intensite']);
		$result->bindValue(':placeMax', $data['placeMax']);
		$result->bindValue(':decouverteMax', $data['decouverteMax']);
		$result->bindValue(':coursId', $data['id_cours']);
		$result->bindValue(':idPlanning', $data['idPlanning']);
		$result->execute();
	} 
	*/



	// Supprimer planning du "date à date"

	// 
		
}