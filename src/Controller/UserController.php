<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Sujet;
use App\Entity\User;
use App\Form\DeleteCommentaireType;
use App\Form\ModifyCommentaireType;
use App\Form\ProfileFormType;
use App\Form\ProfilePasswordFormType;
use App\Form\SujetType;
use App\Repository\CommentaireRepository;
use App\Repository\SujetRepository;
use DateTimeZone;
use Doctrine\DBAL\Types\StringType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/profile")
 */

class UserController extends AbstractController
{
    private $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }
    #[Route('/ajout', name: 'ajout_sujet')]
    public function ajoutSujet(Sujet $sujet = null, Request $request, EntityManagerInterface $manager)
    {
        if ($sujet === null){
            $sujet = new Sujet();
            $date = new \DateTime('@'.strtotime('now'));
            $date->setTimeZone(new DateTimeZone("Europe/Paris"));
            $sujet->setDateCreation($date);
            $sujet->setUser($this->getUser());
        }
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);
        

        if($form->isSubmitted() && $form->isValid())
        {
            
            $manager->persist($sujet);
            $manager->flush();
            $redirect = $sujet->getId();
            return $this->redirectToRoute('vue_sujet', [
                'id' => $redirect
            ]);
        }


        return $this->render('default/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }
    

    #[Route('/edit', name:'profile')]
    public function editProfil(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher, SluggerInterface $slugger)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);
        $oldFile = $this->user->getAvatar();
        $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $avatarFile = $form->get('avatar')->getData();
                    if(is_string($avatarFile)){}
                    else{
                        if ($avatarFile) {
                            
                            $extension = $avatarFile->guessExtension();
                            if ($extension === 'jpg' || $extension === 'png' || $extension === 'jpeg') {
                                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                                $safeFilename = $slugger->slug($originalFilename);
                                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();
                        
                                try {
                                    $avatarFile->move(
                                        $this->getParameter('avatars_directory'),
                                        $newFilename
                                    );
                                } catch (FileException $e) {

                                }

                                    $user->setAvatar($newFilename);
                            }
                            else{
                                $this->addFlash('profil_verification', 'Veuillez saisir un format parmi .jpg, .png, .jpeg');
                                $user->setAvatar($oldFile);
                        }
                           
                    }
                } 

                    $manager->persist($user);
                    $manager->flush();
                    $redirect = $user->getId();
                    return $this->redirectToRoute('profile', [
                        'id' => $redirect,
                        'user' => $user
                    ]);
            }
        return $this->render('default/profil.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/edit_password', name:'profile_password')]
    public function editPassword(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilePasswordFormType::class, $user);
        $form->handleRequest($request);
        $password1 = $form->get('password')->getData();
        $password2 = $form->get('passwordVerif')->getData();

        if($password1 == $password2){
            if($form->isSubmitted() && $form->isValid())
            {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $manager->persist($user);
                $manager->flush();
                $confirm = 'Votre mot de passe a bien été modifié.';
                return $this->redirectToRoute('profile', [
                    'redirect' => $confirm
                ]);
            }
        }
        else{
            $this->addFlash('password_verification', 'Vos mots de passe ne correspondent pas.');
        }

        return $this->render('default/profilPassword.html.twig', [
            'form' => $form->createView(),

        ]);
    }
    #[Route("/edit/{id}", name:"edit_Commentaire", requirements:['id' =>'\d+'], methods:['GET','POST'])]
    public function deleteCommentaire(Commentaire $commentaire, $id, CommentaireRepository $commentaireRepository, EntityManagerInterface $manager, Request $request) : Response{

        $user = $commentaire->getUser();
        // $user = $user->getPseudo();
        $form = $this->createForm(DeleteCommentaireType::class, $commentaire);
        $form2 = $this->createForm(ModifyCommentaireType::class, $commentaire);
        
        $commentaire = $form->getData();
        $commentaire2 = $form2->getData();
            
        $form->handleRequest($request);
        $form2->handleRequest($request);

        if (isset($_POST['supprimer'])){
        if($form->isSubmitted() && $form->isValid())
        {
            $commentaire->setIsDelete(true);
            $manager->persist($commentaire);
            $manager->flush();
            $sujet = $commentaire->getSujet();
            return $this->redirectToRoute('vue_sujet', [
                'id' => $sujet->getId()
            ]);
        }
    }
    if (isset($_POST['enregistrer'])){
        if($form2->isSubmitted() && $form2->isValid())
        {
            $date = new \DateTime('@'.strtotime('now'));
            $date->setTimeZone(new DateTimeZone("Europe/Paris"));

            $commentaire2->setDateModification($date);
            $commentaire2->setIsEdited(true);
            $manager->persist($commentaire2);
            $manager->flush();
            $sujet = $commentaire2->getSujet();
            return $this->redirectToRoute('vue_sujet', [
                'id' => $sujet->getId()
            ]);
        }
    }
        return $this->render('admin/deleteCommentaire.html.twig', [
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'user' => $user
        ]);
    }
    #[Route('/mes-sujets', name: 'mes_sujets')]
    public function mesSujets(SujetRepository $sujetRepository)
    {
        $userId = $this->user->getId(); 
        $sujets = $sujetRepository->findByUserId($userId);
        return $this->render('default/mesSujets.html.twig', [
            'sujets' => $sujets
        ]);
    }
}
