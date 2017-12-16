<?php

namespace WF3\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
     private $PlanningidPlanning;

     private $idusers;

     
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