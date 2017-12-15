<?php
namespace WF3\Domain;

class Sale {
    
    private $id;
    private $amount;
    private $buyerid;
    private $paymentid;
    private $payerid;
    private $abonnementid;
    private $email;
    private $createtime;
    private $phone;
    private $adress;
    private $status;


//id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

//amount
    public function getAmount() {
        return $this->amount;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
        return $this;
    }

//buyerid
    public function getBuyerid() {
        return $this->buyerid;
    }

    public function setBuyerid($buyerid) {
        $this->buyerid = $buyerid;
        return $this;
    }

//paiementid
    public function getPaymentid() {
        return $this->paymentid;
    }

    public function setPaymentid($paymentid) {
        $this->paymentid = $paymentid;
        return $this;
    }


//payerid
    public function getPayerid() {
        return $this->payerid;
    }

    public function setPayerid($payerid) {
        $this->payerid = $payerid;
        return $this;
    }


//abonnementid
    public function getAbonnementid() {
        return $this->abonnementid;
    }

    public function setAbonnementid($abonnementid) {
        $this->abonnementid = $abonnementid;
        return $this;
    }


//email
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }


//createtime
    public function getCreatetime() {
        return $this->createtime;
    }

    public function setCreatetime($createtime) {
        $this->createtime = $createtime;
        return $this;
    }


//phone
    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }


//adress
    public function getAdress() {
        return $this->adress;
    }

    public function setAdress($adress) {
        $this->adress = $adress;
        return $this;
    }


//status
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
}