<?php
namespace WF3\Domain;

class Planing{
    //déclaration des attributs
    private $idPlanning;
    private $date_cours;
    private $duree;
    private $intensite;
    private $place_max;
    private $decouverte_max;
    private $cours_int;
    
    public function getIdPlanning(){
        return $this->id;
    }
    
    public function getDate_cours(){
        return $this->title;
    }
    
    public function getDuree(){
        return $this->content;
    }
    
    public function getIntensite(){
        return $this->date_publi;
    }
    
    public function getPlace_max(){
        return $this->place_max;
    }
    
    public function getDecouverte_max($id){
        return $this;
    }
    public function getCours_int(){
        return $this;
    }
    
    //setters

    public function setIdPlanning($IdPlanning){
        if(!empty($IdPlanning) AND is_string($IdPlanning)){
            $this->IdPlanning = $IdPlanning; 
        }
    }

    public function setdate_cours($date_cours){
        if(!empty($date_cours) AND is_string($date_cours)){
            $this->date_cours = $date_cours; 
        }
    }

    public function setduree($duree){
        if(!empty($duree) AND is_string($duree)){
            $this->duree = $duree; 
        }
    }

    public function setintensite($intensite){        
            $this->intensite = $intensite;         
    }
    
    public function setPlace_max($place_max){        
        $this->place_max = $place_max;         
    }

    public function setDecouverte_max($decouverte_max){        
    $this->decouverte_max = $decouverte_max;         
    }

    public function setCours_int($cours_int){        
        $this->cours_int = $cours_int;         
        }
}