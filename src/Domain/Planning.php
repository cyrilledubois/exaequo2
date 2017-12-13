<?php
namespace WF3\Domain;

class Planning{
    //déclaration des attributs
    private $id;
    private $datecours;
    private $duree;
    private $placemax;
    private $decouverte_max;
    private $coursid;
    
    public function getId(){
        return $this->id;
    }
    
    public function getDate_cours(){
        return $this->date_cours;
    }
    
    public function getDuree(){
        return $this->duree;
    }
    
    public function getPlace_max(){
        return $this->place_max;
    }
    
    public function getDecouverte_max(){
        return $this->decouverte_max;
    }
    public function getCours_id(){
        return $this->coursid;
    }
    
    //setters

    public function setId($id){
        if(!empty($id) && is_int($id)){
            $this->id = $id; 
        }
    }

    public function setDate_cours($date_cours){
        if(!empty($date_cours)){
            $this->date_cours = $date_cours; 
        }
    }

    public function setDuree($duree){
        if(!empty($duree) AND is_int($duree)){
            $this->duree = $duree; 
        }
    }

    public function setPlace_max($place_max){
        if(!empty($place_max) AND is_int($place_max)){           
        $this->place_max = $place_max;
        }         
    }

    public function setDecouverte_max($decouverte_max){    
        if(!empty($decouverte_max) AND is_int($decouverte_max)){       
        $this->decouverte_max = $decouverte_max;    
        }     
    }

    public function setCours_id($cours_id){   
        if(!empty($cours_id) AND is_int($cours_id)){        
        $this->cours_id = $cours_id;         
        }
    }
}