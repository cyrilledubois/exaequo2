<?php
namespace WF3\DAO;

class PlanningDAO extends DAO{

	//je crée un attribut qui va contenir un objet de classe UsersDAO (la lcasse qui nous permet de manipuler la table users)
	private $usersDAO;
	//le setter associé
	public function setUsersDAO(UsersDAO $usersDAO){
		$this->usersDAO = $usersDAO;
	}

	// Select planning generale
    public function selectPlanning(){
        $result = $this->bdd->query('SELECT * FROM planning ORDER BY date_cours ASC LIMIT 10');
    }

	// Select planning de "date à date"
	public function selectPeriod(){
		$result = $this->bdd->prepare('SELECT * FROM planning WHERE date_cours = :date_cours');
		$result->bindValue(':date_cours', $date_cours, \PDO::PARAM_INT);
		$result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}
	
	// Update periode du planning 
	public function updatePeriodPlanning(Application $app, Request $request, $id){
		//on récupère les infos de la periode 
				$period = $app['dao.planning']->find($id);
				
		//on crée le planning et on lui passe la periode en paramètre
        //il va utiliser $article pour pré remplir les champs
		$planning = $app['form.factory']->create(PlanningType::class, $period);		
		
		$planning->handleRequest($request);

		if($planning->isSubmitted() && $planning->isValid()){
            //si le formulaire a été soumis
            //on update avec les données envoyées par l'utilisateur
            $app['dao.planning']->update($id, array(
                'cours'=>$planning->getCours(),
                'duree_cours'=>$planning->getduree_cours(),
                'author'=>$planning->getAuthor()->getId()
            ));
        }
	}


	// Supprimer planning du "date à date"

	// 
		
}