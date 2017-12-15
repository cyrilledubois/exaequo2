<?php
namespace WF3\Domain;

class abonnement {
    
    private $id;
    private $nom;
    private $prix;
    private $descriptif;


//id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

//nom
    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
        return $this;
    }

//prix
    public function getPrix() {
        return $this->prix;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
        return $this;
    }

//descriptif
    public function getDescriptif() {
        return $this->descriptif;
    }

    public function setDescriptif($descriptif) {
        $this->descriptif = $descriptif;
        return $this;
    }
}