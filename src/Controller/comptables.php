<?php
namespace App\Controller;

// include 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\Vehicule;

use App\Entity\FraisHorsForfait;
use App\Entity\User;

use App\Entity\FraisForfait;

class comptables extends AbstractController
{

    // Ajout du gestionnaire d'entité

    public function __construct (EntityManagerInterface $em)
    {
        $this->EntityManager = $em;
    }

     /**
     * @Route("/comptables/accueil_comptable", name = "accueil_comptables")
     * @Method({"GET", "POST"})
    */
    
    public function afficherBdd() : Response
    {
        $repository26 = $this->getDoctrine()
            ->getRepository(FraisForfait::class)
            ->findBy(['etat' => "En attente"]);

        $repository27 = $this->getDoctrine()
            ->getRepository(FraisForfait::class)
            ->findBy(['etat' => "Rejeté"]);
        
        $repository28 = $this->getDoctrine()
            ->getRepository(FraisForfait::class)
            ->findBy(['etat' => "Validé"]);

        $repository26BIS = $this->getDoctrine()
        ->getRepository(FraisHorsForfait::class)
        ->findBy(['etat' => "En attente"]);

        $repository27BIS = $this->getDoctrine()
            ->getRepository(FraisHorsForfait::class)
            ->findBy(['etat' => "Rejeté"]);
        
        $repository28BIS = $this->getDoctrine()
            ->getRepository(FraisHorsForfait::class)
            ->findBy(['etat' => "Validé"]);

        return $this->render('/comptables/accueil_comptable.html.twig', [
            "attente" => $repository26,
            "rejete" => $repository27,
            "valide" => $repository28,
            "attenteBIS" => $repository26BIS,
            "rejeteBIS" => $repository27BIS,
            "valideBIS" => $repository28BIS
        ]); 
    }

    /**
     * @Route("/comptables/fiche_comptable", name = "liste_visiteur")
     * @Method({"GET", "POST"})
    */
    
    public function Afficher_liste_visiteur() : Response
    {

        setlocale(LC_TIME, "fr_FR");
        $mois_actuel = date("m / Y");

        $repository = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        if (!$repository) {
            throw $this->createNotFoundException(
                'Il n\'existe aucun utilisateur en base de donnée'
            );
        }

        return $this->render('comptables/liste_visiteur.html.twig', [
            "liste_visiteur" => $repository,
            'date' => $mois_actuel
        ]); 
    }

     /**
     * @Route("/comptables/fiche_frais", name = "suivi_frais")
     * @Method({"GET", "POST"})
    */
    
    public function suivi_fiches_frais() : Response
    {
        setlocale(LC_TIME, "fr_FR");
        $mois_actuel = date("m / Y");
        
        $user = $this->getUser();
        $user = $user->getId();

        $repository = $this->getDoctrine()
            ->getRepository(FraisHorsForfait::class)
            ->findAll();

        $repository2 = $this->getDoctrine()
            ->getRepository(FraisForfait::class)
            ->findAll();

        $repository3 = $this->getDoctrine()
            ->getRepository(Vehicule::class)
            ->findAll();

        $repository4 = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        
        return $this->render('comptables\fiche_frais.html.twig', [
            "horsForfait" => $repository,
            "Forfait" => $repository2,
            "vehicule" => $repository3,
            "user" => $repository4,
            'date' => $mois_actuel
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name = "supprimer")
    */

    public function supprimer_frais_forfait(int $id) : Response{

        $entityManager=$this->getDoctrine()->getManager();
        
        $repository20 = $this->getDoctrine()
                ->getRepository(FraisForfait::class)
                ->find($id);

        $entityManager->remove($repository20);
        $entityManager->flush();

        $this->addFlash('success', 'Suppression effectuée'); 
        
        return $this->redirectToRoute('suivi_frais'); 
    }

    /**
     * @Route("/valide/{id}", name = "valide")
    */

    public function valider_frais_forfait(int $id) : Response{

        $entityManager=$this->getDoctrine()->getManager();
        
        $repository21 = $this->getDoctrine()
                ->getRepository(FraisForfait::class)
                ->find($id);

        $repository21->setEtat('Validé');
        $entityManager->flush();

        $this->addFlash('valide', 'Validation effectuée'); 
        
        return $this->redirectToRoute('suivi_frais'); 
    }

    /**
     * @Route("/attente/{id}", name = "attente")
    */

    public function attente_frais_forfait(int $id) : Response{

        $entityManager=$this->getDoctrine()->getManager();
        
        $repository21 = $this->getDoctrine()
                ->getRepository(FraisForfait::class)
                ->find($id);

        $repository21->setEtat('En attente');
        $entityManager->flush();

        $this->addFlash('attente', 'Mise en attente effectuée'); 
        
        return $this->redirectToRoute('suivi_frais'); 
    }

    /**
     * @Route("/rejete/{id}", name = "rejete")
    */

    public function rejeter_frais_forfait(int $id) : Response{

        $entityManager=$this->getDoctrine()->getManager();
        
        $repository21 = $this->getDoctrine()
                ->getRepository(FraisForfait::class)
                ->find($id);

        $repository21->setEtat('Rejeté');
        $entityManager->flush();

        $this->addFlash('rejete', 'Rejet effectué'); 

        return $this->redirectToRoute('suivi_frais'); 
    }

    
    /**
     * @Route("/comptables/info/{id}/{proprietaire}", name = "info")
    */

    public function info_frais_forfait(int $id, int $proprietaire) : Response{

        $repository23 = $this->getDoctrine()
                ->getRepository(FraisForfait::class)
                ->findBy(['id' => $id]);

        $repository24 = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $repository25 = $this->getDoctrine()
            ->getRepository(Vehicule::class)
            ->findBy(['proprietaire' => $proprietaire]);


        return $this->render('comptables\info.html.twig', [
            "Forfait" => $repository23,
            "user" => $repository24,
            "vehicule" => $repository25
        ]);
    }


    /**
     * @Route("/valide2/{id}", name = "valide2")
    */

    public function valider_frais_horfait(int $id) : Response{

        $entityManager=$this->getDoctrine()->getManager();
        
        $repository21 = $this->getDoctrine()
                ->getRepository(FraisHorsForfait::class)
                ->find($id);

        $repository21->setEtat('Validé');
        $entityManager->flush();

        $this->addFlash('valide', 'Validation effectuée'); 
        
        return $this->redirectToRoute('suivi_frais'); 
    }

    /**
     * @Route("/attente2/{id}", name = "attente2")
    */

    public function attente_frais_horfait(int $id) : Response{

        $entityManager=$this->getDoctrine()->getManager();
        
        $repository21 = $this->getDoctrine()
                ->getRepository(FraisHorsForfait::class)
                ->find($id);

        $repository21->setEtat('En attente');
        $entityManager->flush();

        $this->addFlash('attente', 'Mise en attente effectuée'); 

        return $this->redirectToRoute('suivi_frais'); 
    }

    /**
     * @Route("/rejete2/{id}", name = "rejete2")
    */

    public function rejeter_frais_horfait(int $id) : Response{

        $entityManager=$this->getDoctrine()->getManager();
        
        $repository21 = $this->getDoctrine()
                ->getRepository(FraisHorsForfait::class)
                ->find($id);

        $repository21->setEtat('Rejeté');
        $entityManager->flush();

        $this->addFlash('rejete', 'Rejet effectué'); 
       
        return $this->redirectToRoute('suivi_frais'); 
    }

    
    /**
     * @Route("/comptables/info2/{id}/{proprietaire}", name = "info2")
    */

    public function info_frais_horfait(int $id, int $proprietaire) : Response{

        $repository23 = $this->getDoctrine()
                ->getRepository(FraisHorsForfait::class)
                ->findBy(['id' => $id]);

        $repository24 = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $repository25 = $this->getDoctrine()
            ->getRepository(Vehicule::class)
            ->findBy(['proprietaire' => $proprietaire]);


        return $this->render('comptables\info2.html.twig', [
            "hors_forfait" => $repository23,
            "user" => $repository24,
            "vehicule" => $repository25
        ]);
    }    
}
