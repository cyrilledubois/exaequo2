<?php
namespace WF3\Domain;

class Planning{
    //déclaration des attributs
    private $idPlanning;
    private $date_cours;
    private $duree;
    private $place_max;
    private $decouverte_max;
    private $cours_id;
    
    public function getIdPlanning(){
        return $this->idPlanning;
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
    public function getIdCours(){
        return $this->cours_id;
    }
    
    //setters

    public function setIdPlanning($idPlanning){
        if(!empty($idPlanning) && is_int($idPlanning)){
            $this->idPlanning = $idPlanning; 
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

    public function setDecouverte_max($decouverte_max){    
        if(!empty($decouverte_max) AND is_int($decouverte_max)){       
        $this->decouverte_max = $decouverte_max;    
        }     
    }

    public function setIdCours($cours_id){   
        if(!empty($cours_id) AND is_int($cours_id)){        
        $this->cours_id = $cours_id;         
        }
}