<?php
namespace WF3\Domain;

class PlanningModel{
    //déclaration des attributs
    private $id;
    private $jour;
    private $heure;
    private $duree;
    private $placemax;
    private $coursid;
    
    public function getId(){
        return $this->id;
    }
    
    public function getJour(){
        return $this->jour;
    }
    
    public function getHeure(){
        return $this->heure;
    }

    public function getDuree(){
        return $this->duree;
    }
    
    public function getPlacemax(){
        return $this->placemax;
    }
    
    public function getCoursid(){
        return $this->coursid;
    }
    
    //setters

    public function setId($id){
        if(!empty($id) && is_numeric($id)){
            $this->id = $id; 
        }
    }

    public function setJour($jour){
        if(!empty($jour)){
            $this->jour = $jour; 
        }
    }

    public function setHeure($heure){
        if(!empty($heure)){
            $this->heure = $heure; 
        }
    }

    public function setDuree($duree){
        if(!empty($duree) AND is_numeric($duree)){
            $this->duree = $duree; 
        }
    }

    public function setPlacemax($placemax){
        if(!empty($placemax) AND is_numeric($placemax)){           
        $this->placemax = $placemax;
        }         
    }

    public function setCoursId($coursid){   
        if(!empty($coursid) AND is_numeric($coursid)){        
        $this->coursid = $coursid;         
        }
    }
}