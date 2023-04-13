<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\Sujet;
use App\Form\CommentaireType;
use App\Form\ModifyCommentaireType;
use App\Repository\CategorieRepository;
use App\Repository\CommentaireRepository;
use App\Repository\SujetRepository;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints\Collection;

class DefaultController extends AbstractController 
{
    private $user;
    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }


    #[Route("/sujets-{categorie}", name:"liste_sujets", methods:['GET'])]
    public function listeSujets(SujetRepository $sujetRepository, $categorie): Response
    {
        $sujet = $sujetRepository->findOneByIdJoinedToCategory($categorie);

        return $this->render('default/listeSujets.html.twig', [
            'sujets' => $sujet,
            'categorie' => $categorie
        ]);
    }

    #[Route('/', name: 'liste_categories', methods:['GET'])]
    public function listeCategories(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        return $this->render('default/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route("/forum/{id}", name:"vue_sujet", requirements:['id' =>'\d+'])]
    public function vueSujet(Sujet $sujet, SujetRepository $sujetRepository, $id,CommentaireRepository $commentaireRepository, Request $request, EntityManagerInterface $manager): Response
    {
        $test = $this->getUser();
        if($this->user !== null){
            
            $date = new \DateTime('@'.strtotime('now'));
            $date->setTimeZone(new DateTimeZone("Europe/Paris"));

            $commentaire = new Commentaire();
            $commentaire->setDateCommentaire($date);
            $commentaire->setSujet($sujet);
            $commentaire->setUser($test);
            $commentaire->setIsDelete(false);
            $commentaire->setIsEdited(false);
            $form = $this->createForm(CommentaireType::class, $commentaire);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $manager->persist($commentaire);
                $manager->flush();
                return $this->redirectToRoute('vue_sujet', ['id' => $sujet->getId()]);
            }

            return $this->render('default/sujet.html.twig', [
                'sujet' => $sujet,
                'form' => $form->createView(),
                'user' => $test
            ]);
        }
        else {
            return $this->render('default/sujet.html.twig', [
                'sujet' => $sujet,
                'user' => $test
            ]);
        }
    }
    
}
