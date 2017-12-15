<?php
namespace WF3\DAO;

class AboDAO extends DAO{

    public function selectAbo($id){
		$result = $this->bdd->prepare('SELECT * FROM historiqueabonnement 
        INNER JOIN users ON users.id = historiqueabonnement.usersId 
        INNER JOIN abonnement ON historiqueabonnement.abonnementId = abonnement.id 
        WHERE users.id =:id');
        $result->bindValue(':id', $id, \PDO::PARAM_INT);
        $result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
    }


    // Fonction qui permet à un utilisateur connecté de s'abonner 
    public function choixAbo($idAdo, $idUser){
        $result = $this->bdd->prepare('INSERT INTO historique abonnement (datedebut, datefin, abonnemenid, usersId) VALUES (:datedebut, :datefin, :idabo, :iduser)');
        $result->bindValue(':datedebut', $datedebut, \PDO::PARAM_INT);
        $result->bindValue(':datefin', $datefin, \PDO::PARAM_INT);
        $result->bindValue(':idabo', $idabo, \PDO::PARAM_INT);
        $result->bindValue(':iduser', $iduser, \PDO::PARAM_INT);
        $result->execute();
        return $result->fetchALL(\PDO::FETCH_ASSOC);
    }



}