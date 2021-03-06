<?php
namespace WF3\Domain;

class Planning{
    //déclaration des attributs
    private $id;
    private $datecours;
    private $duree;
    private $placemax;
    private $coursid;
    private $userid;
    private $PlanningidPlanning;
    
    public function getId(){
        return $this->id;
    }
    
    public function getDatecours(){
        return $this->datecours;
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
        if(!empty($id) && is_nuemric($id)){
            $this->id = $id; 
        }
    }

    public function setDatecours($datecours){
        if(!empty($datecours)){
            $this->datecours = $datecours; 
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

    public function setCoursid($coursid){   
        if(!empty($coursid) AND is_numeric($coursid)){        
        $this->coursid = $coursid;         
        }
    }
    public function getUserId(){
       return $this->userid;
   }

   public function setUserId($userid) {
       $this->userid = $userid;
       return $this;
   }

   public function getPlanningIdPlanning(){
       return $this->PlanningidPlanning;
   }

   public function setPlanningIdPlanning($PlanningidPlanning) {
       $this->PlanningidPlanning = $PlanningidPlanning;
       return $this;
   }




}