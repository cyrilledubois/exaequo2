<?php

namespace WF3\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /**
     * User id.
     *
     * @var integer
     */
    private $id;

    /**
     * User name.
     *
     * @var string
     */
    private $username;

    /**
     * User email.
     *
     * @var string
     */
    private $email;

    /**
     * User phone.
     *
     * @var string
     */
    private $phone;

    private $password;

    private $salt;

    private $role;

    private $lastname;

    private $firstname;

    private $sex;

    private $datedenaissance;

    private $adress;

    private $cp;

    private $town;

    private $profession;

    private $groupeclient;

    private $userid;

    private $PlanningidPlanning;

    
   


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($name) {
        $this->username = $name;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array($this->getRole());
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
        // Nothing to do here
    }

    //prenom
     public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }

    //sexe
     public function getSex() {
        return $this->sex;
    }

    public function setSex($sex) {
        $this->sex = $sex;
        return $this;
    }

    //dateDeNaissance
     public function getDateDeNaissance() {
        return $this->datedenaissance;
    }

    public function setDateDeNaissance($datedenaissance) {
        $this->datedenaissance = $datedenaissance;
        return $this;
    }

    //adresse
     public function getAdress() {
        return $this->adress;
    }

    public function setAdress($adress) {
        $this->adress = $adress;
        return $this;
    }

    //codePostal
     public function getCp() {
        return $this->cp;
    }

    public function setCp($cp) {
        $this->cp = $cp;
        return $this;
    }

    //ville
     public function getTown() {
        return $this->town;
    }

    public function setTown($town) {
        $this->town = $town;
        return $this;
    }

    //profession
     public function getprofession() {
        return $this->profession;
    }

    public function setprofession($profession) {
        $this->profession = $profession;
        return $this;
    }

    //groupeClient
     public function getGroupeClient() {
        return $this->groupeclient;
    }

    public function setGroupeClient($groupeclient) {
        $this->groupeclient = $groupeclient;
        return $this;
    }


    //pseudo
     public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }
    
    public function getUserId(){
        return $this->userid;
    }

    public function setUserId($userid) {
        $this->groupeclient = $groupeclient;
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