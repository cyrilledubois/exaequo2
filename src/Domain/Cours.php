<?php
namespace WF3\Domain;

class Cours{
    //déclaration des attributs
    private $nom;
    private $intensite;
    private $id;
 
    
    public function getNom(){
        return $this->nom;
    }
    
    public function getDate_cours(){
        return $this->intensite;
    }
    
    public function getId(){
        return $this->id;
    }
    
    //setters

    public function setNom($nom){
        if(!empty($nom) && is_int($nom)){
            $this->nom = $nom; 
        }
    }

    public function setIntensite($intensite){
        if(!empty($intensite) && is_string($intensite)){
            $this->intensite = $intensite; 
        }
    }

    public function setId($id){
        if(!empty($id) AND is_int($id)){
            $this->id = $id; 
        }
    }
}

   