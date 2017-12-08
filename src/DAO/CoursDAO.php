<?php 
namespace WF3\DAO;

class CoursDAO extends DAO {

    public function selectCours($id_cours){
        $result = $this->bdd->prepare('SELECT * FROM cours WHERE id = :id');
        
		return $result->fetchALL(\PDO::FETCH_ASSOC);
    }

}
