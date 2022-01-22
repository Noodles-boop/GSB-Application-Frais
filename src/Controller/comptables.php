<?php
namespace App\Controller;

// include 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\Vehicule;
use App\Form\VehiculeType;

use App\Entity\FraisHorsForfait;
use App\Entity\User;
use App\Form\FraisHorsForfaitType;

use App\Entity\FraisForfait;
use App\Form\FraisForfaitType;

class comptables extends AbstractController
{

    private $em;

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

        return $this->render('/comptables/accueil_comptable.html.twig', [
            "attente" => $repository26,
            "rejete" => $repository27,
            "valide" => $repository28
        ]); 
    }

    /**
     * @Route("/comptables/fiche_comptable", name = "liste_visiteur")
     * @Method({"GET", "POST"})
    */
    
    public function Afficher_liste_visiteur() : Response
    {
        setlocale(LC_TIME, "fr_FR");
        $mois_actuel = date("F Y");

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
    
    public function suivi_fiche_frais() : Response
    {
        setlocale(LC_TIME, "fr_FR");
        $mois_actuel = date("F Y");
        
        $user = $this->getUser();
        $user = $user->getId();

        $repository = $this->getDoctrine()
            ->getRepository(FraisHorsForfait::class)
            ->findBy(['proprietaire' => $user]);

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
            "Hors_Forfait" => $repository,
            "Forfait" => $repository2,
            "vehicule" => $repository3,
            "user" => $repository4,
            'date' => $mois_actuel
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name = "supprimer")
    */

    public function supprimer(int $id) : Response{

        $entityManager=$this->getDoctrine()->getManager();
        
        $repository20 = $this->getDoctrine()
                ->getRepository(FraisForfait::class)
                ->find($id);


        $entityManager->remove($repository20);
        $entityManager->flush();

        $this->addFlash('success', 'Suppression effectuée'); // Affiche un message de confirmation sur la page d'accueil
        
        // return $this->render('comptables\fiche_frais.html.twig', [
        // ]);

        return $this->redirectToRoute('suivi_frais'); // Rediriger vers la page d'accueil
    }

    /**
     * @Route("/valide/{id}", name = "valide")
    */

    public function valide(int $id) : Response{

        $entityManager=$this->getDoctrine()->getManager();
        
        $repository21 = $this->getDoctrine()
                ->getRepository(FraisForfait::class)
                ->find($id);

        $repository21->setEtat('Validé');
        $entityManager->flush();

        $this->addFlash('success', 'Frais validé'); // Affiche un message de confirmation sur la page d'accueil
        
        // return $this->render('comptables\fiche_frais.html.twig', [
        // ]);

        return $this->redirectToRoute('suivi_frais'); // Rediriger vers la page d'accueil
    }

    /**
     * @Route("/attente/{id}", name = "attente")
    */

    public function attente(int $id) : Response{

        $entityManager=$this->getDoctrine()->getManager();
        
        $repository21 = $this->getDoctrine()
                ->getRepository(FraisForfait::class)
                ->find($id);

        $repository21->setEtat('En attente');
        $entityManager->flush();

        $this->addFlash('success', 'Frais mis en attente'); // Affiche un message de confirmation sur la page d'accueil
        
        // return $this->render('comptables\fiche_frais.html.twig', [
        // ]);

        return $this->redirectToRoute('suivi_frais'); // Rediriger vers la page d'accueil
    }

    /**
     * @Route("/rejete/{id}", name = "rejete")
    */

    public function rejete(int $id) : Response{

        $entityManager=$this->getDoctrine()->getManager();
        
        $repository21 = $this->getDoctrine()
                ->getRepository(FraisForfait::class)
                ->find($id);

        $repository21->setEtat('Rejeté');
        $entityManager->flush();

        $this->addFlash('success', 'Frais rejeté'); // Affiche un message de confirmation sur la page d'accueil
        
        // return $this->render('comptables\fiche_frais.html.twig', [
        // ]);

        return $this->redirectToRoute('suivi_frais'); // Rediriger vers la page d'accueil
    }

    /**
     * @Route("/comptables/info/{id}/{proprietaire}", name = "info")
    */

    public function info(int $id, int $proprietaire) : Response{

        $repository23 = $this->getDoctrine()
                ->getRepository(FraisForfait::class)
                ->findBy(['id' => $id]);

        $repository24 = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $repository25 = $this->getDoctrine()
            ->getRepository(Vehicule::class)
            ->findBy(['proprietaire' => $proprietaire]);

        // return $this->redirectToRoute('suivi_frais'); // Rediriger vers la page d'accueil
        return $this->render('comptables\info.html.twig', [
            "Forfait" => $repository23,
            "user" => $repository24,
            "vehicule" => $repository25
        ]);
    }

    
}
