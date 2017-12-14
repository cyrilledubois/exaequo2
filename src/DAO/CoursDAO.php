<?php 
namespace WF3\DAO;

class CoursDAO extends DAO {

    public function selectCours($id_cours){
        $result = $this->bdd->prepare('SELECT * FROM cours WHERE id = :id');
        
		return $result->fetchALL(\PDO::FETCH_ASSOC);
    }

    public function selectPlanning($id_cours){
    $PlanningModif = $this->bdd->prepare('SELECT * FROM planning WHERE id_cours = :id_cours');
    $result->bindValue(':id_cours', $id_cours);
    return $PlanningModif->fetchAll(\PDO::FETCH_ASSOC);
    }

}