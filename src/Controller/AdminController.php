<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Commentaire;
use App\Form\DeleteCommentaireType;
use App\Repository\CommentaireRepository;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  @Route("/admin")
 */

class AdminController extends AbstractController
{
    
}
