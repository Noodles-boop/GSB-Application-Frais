<?php
namespace App\Controller;

// include 

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EquipementRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\Vehicules;
use App\Form\VehiculesType;

use App\Entity\FraisHorsForfait;
use App\Form\FraisHorsForfaitType;

use App\Entity\FraisForfait;
use App\Form\FraisForfaitType;


class visiteurs extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/accueil_visiteur", name = "accueil_visiteurs")
     * @Method({"POST"})
    */
    
    public function Accueil() : Response 
    {
        return $this->render('visiteurs/accueil_visiteur.html.twig', [

        ]); 
    }

    /**
     * @Route("/frais_visiteur", name = "fiche_frais")
     * @Method({"POST"})
    */
    
    public function Saisir_frais(Request $request) : Response           
    {
        $fiche = new FraisForfait ();
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(FraisForfaitType::class, $fiche); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
       
            $entityManager->persist($fiche);
            $entityManager->flush();
        }

        return $this->render('visiteurs\fiche_visiteur.html.twig',[    // Création du formulaire par symfony
            'form' => $form->createView()
        ]); 
    }


        
    // public function Saisir_frais_2(Request $request) : Response    
    // {
    //     $fiche2 = new FraisHorsForfait ();
    //     $entityManager = $this->getDoctrine()->getManager();

    //     $form2 = $this->createForm(FraisHorsForfaitType::class, $fiche2); 
    //     $form2->handleRequest($request);

    //     if ($form2->isSubmitted() && $form2->isValid()) {
       
    //         $entityManager->persist($fiche2);
    //         $entityManager->flush();
    //     }

    //     return $this->render('visiteurs\fiche_visiteur.html.twig',[ // Création du formulaire par symfony
    //         'form' => $form->createView()
    //     ]); 
    // }
       

     /**
     * @Route("/suivi_frais", name = "visiteur_frais")
     * @Method({"POST"})
    */
    
    public function Suivre_frais() : Response 
    {
        return $this->render('visiteurs/remboursement_visiteur.html.twig', [

        ]); 
    }

    /**
     * @Route("/vehicule", name = "vehicules")
     * @Method({"POST"})
    */
    
    public function Saisi_vehicule(Request $request) : Response 
    {

        $Vehicule = new Vehicules ();
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(VehiculesType::class, $Vehicule); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
       
            $entityManager->persist($Vehicule);
            $entityManager->flush();

            $this->addFlash('success', 'Véhicule ajouté'); // Affiche un message de confirmation sur la page d'accueil   
            return $this->redirectToRoute('accueil_visiteurs');  // Rediriger vers la page d'accueil
             
        }

        return $this->render('visiteurs\vehicule_visiteur.html.twig',[ // Création du formulaire par symfony
            'form' => $form->createView()
        ]); 
    }

}
