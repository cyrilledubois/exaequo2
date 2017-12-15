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
    public function getInfoReserv(){ 
        $ecart = $j - date('w');
        $datecible = new \DateTime;
        //ajoute l'écart en jour pour aller à la date cible
        $datecible->modify('+'.$ecart.' day');
        //Transforme ensuite le format pour qu'il soit compatible SQL
        $dataffich = $datecible->format('Y-m-d');
        $result = $this->bdd->prepare('SELECT * FROM users INNER JOIN users_has_planning ON users_has_planning.users_id = users.id 
        INNER JOIN planning ON users_has_planning.Planning_idPlanning = planning.id
        INNER JOIN cours ON planning.cours_id=cours.id WHERE planning.date_cours = :date_cours');
        $result->bindValue(':date_cours', $dataffich, \PDO::PARAM_INT);
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
}