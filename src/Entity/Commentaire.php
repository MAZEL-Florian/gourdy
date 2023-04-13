<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $contenu;

    #[ORM\Column(type: 'datetime')]
    private $dateCommentaire;

    #[ORM\ManyToOne(targetEntity: Sujet::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private $sujet;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentaries')]
    #[ORM\JoinColumn(nullable: false)]
    private $User;

    #[ORM\Column(type: 'boolean')]
    private $is_Delete;

    #[ORM\Column(type: 'boolean')]
    private $is_edited;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateModification;

    public function __construct()
    {   
        $this->dateCommentaire = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->dateCommentaire;
    }

    public function setDateCommentaire(\DateTimeInterface $dateCommentaire): self
    {
        $this->dateCommentaire = $dateCommentaire;

        return $this;
    }

    public function getSujet(): ?Sujet
    {
        return $this->sujet;
    }

    public function setSujet(?Sujet $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getIsDelete(): ?bool
    {
        return $this->is_Delete;
    }

    public function setIsDelete(bool $is_Delete): self
    {
        $this->is_Delete = $is_Delete;

        return $this;
    }

    public function getIsEdited(): ?bool
    {
        return $this->is_edited;
    }

    public function setIsEdited(bool $is_edited): self
    {
        $this->is_edited = $is_edited;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(?\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;

        return $this;
    }
}
