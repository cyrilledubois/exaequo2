<?php

namespace WF3\Domain;

use Symfony\Component\Security\Core\User\UserInterface;


class Abo
{
    /**
     * User id.
     *
     * @var integer
     */
    private $id;

    private $datedebut;

    private $datefin;

    private $usersId;

    private $abonnementId;
    

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getDatedebut() {
        return $this->datedebut;
    }

    public function setDatedebut($datedebut) {
        $this->datedebut = $datedebut;
        return $this;
    }

    public function getDatefin() {
        return $this->datefin;
    }

    public function setDateFin($datefin) {
        $this->datefin = $datefin;
        return $this;
    }

    public function getUsersId() {
        return $this->usersId;
    }

    public function setUsersId($usersId) {
        $this->usersId = $usersId;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAbonnementId() {
        return $this->abonnementId;
    }

    public function setAbonnementId($abonnementId) {
        $this->abonnementId = $abonnementId;
        return $this;
    }
