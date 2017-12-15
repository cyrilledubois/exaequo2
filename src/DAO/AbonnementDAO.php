<?php

namespace WF3\DAO;


class AbonnementDAO extends DAO {
    
    //permet de récupérer le prix
    public function loadPrix($id) {
        $result = $this->bdd->query('SELECT prix FROM abonnement');
        return $result;
    }
}