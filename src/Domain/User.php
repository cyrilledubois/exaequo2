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

    private $first_name;

    private $sex;

    private $date_de_naissance;

    private $adress;

    private $cp;

    private $town;

    private $profession;

    private $groupe_client;


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
        return $this->first_name;
    }

    public function setFirstname($first_name) {
        $this->$first_name = $first_name;
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
        return $this->date_de_naissance;
    }

    public function setDateDeNaissance($date_de_naissance) {
        $this->date_de_naissance = $date_de_naissance;
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
        return $this->groupe_client;
    }

    public function setGroupeClient($groupe_client) {
        $this->groupe_client = $groupe_client;
        return $this;
    }

}