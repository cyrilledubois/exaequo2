<?php

namespace WF3\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use WF3\Domain\User;

class UserDAO extends DAO implements UserProviderInterface
{
    
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $sql = "select * from " . $this->getTableName() . " where username=?";
        $row = $this->getDb()->fetchAssoc($sql, array($username));

        if ($row)
            return $this->buildObject($row);
        else
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'WF3\Domain\User' === $class;
    }


    // Appelle tout les users
    public function getAllUser(){
        $users = $this->bdd->query('SELECT * FROM users');
        $users->execute();
		return $users->fetchALL(\PDO::FETCH_ASSOC);
    }

// Requête pour utilisateur inscrit / par cours
    public function getInfoReserv($dataffich){ 
        $result = $this->bdd->prepare('SELECT * FROM users INNER JOIN usershasplanning ON usershasplanning.usersid = users.id 
        INNER JOIN planning ON usershasplanning.PlanningidPlanning = planning.id
        INNER JOIN cours ON planning.coursid=cours.id WHERE planning.datecours LIKE :datecours ');
        $result->bindValue(':datecours', $dataffich . '%', \PDO::PARAM_INT);
        $result->execute();
		return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    //pour récupérer les cours par utilisateur
    public function getCoursByUser($id){
       $result = $this->bdd->prepare('SELECT * FROM usershasplanning
       INNER JOIN planning ON usershasplanning.PlanningidPlanning = planning.id
       INNER JOIN cours ON cours.id = planning.coursid
       INNER JOIN users ON usershasplanning.usersid = users.id
       WHERE usershasplanning.usersid = :id');
       $result->bindValue(':id', $id, \PDO::PARAM_INT);
       $result->execute();
       return $result->fetchAll(\PDO::FETCH_ASSOC);
   }



//mot de passe perdu:
public function mdpPerdu() {
    //on vérifie s'il y a une entrée dans la base qui correspond à l'email envoyé dans le formulaire
        $resultat = $bdd->prepare('SELECT password FROM users WHERE email = :email');
        $resultat->bindValue(':email', trim($_POST['email']));
        $resultat->execute();
}


    public function reservAction($userId, $planningId){
        $result = $this->bdd->prepare('INSERT INTO usershasplanning (usersid, PlanningidPlanning) VALUES (:usersid, :PlanningidPlanning)');
        $result->bindValue(':usersid', $userId);
        $result->bindValue(':PlanningidPlanning', $planningId);
        return $result->execute();
        //\PDO::PARAM_INT);
    }

}