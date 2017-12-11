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
        $result = $this->bdd->query('SELECT * FROM user BY id ASC LIMIT 0, 25');
    }

// Requête pour utilisateur inscrit / par cours
    public function getInfoReserv($cours_id){
        $result = $this->bdd->prepare('SELECT users_id, cours_id FROM reservation WHERE id = :id ');
        $result->bindValue(':id', $cours_id, \PDO::PARAM_INT);
        $result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
    }
     
}