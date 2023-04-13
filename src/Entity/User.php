<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Un autre utilisateur possède déjà cette adresse mail.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Assert\Email(
     *     message = "{{ label }} {{ value }} est invalide car elle est utilisée par un autre utilisateur ou n'a pas la bonne syntaxe (exemple@exemple.fr)."
     * )
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    /**
     * @Assert\Length(
     * min = 4,
     * max = 16,
     * minMessage="Le pseudo doit faire au moins {{ limit }} caractères.",
     * maxMessage="Le pseudo doit faire moins de {{ limit }} caractères."
     * )
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $pseudo;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Sujet::class, orphanRemoval: true)]
    private $subjects;

    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ['default' => 'default_avatar.png'])]
    private $avatar;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Commentaire::class, orphanRemoval: true)]
    private $commentaries;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $signature;


    // #[ORM\ManyToOne(targetEntity: Sujet::class, inversedBy: 'User')]
    // private $sujet;

    public function __construct()
    {
        $this->commentaries = new ArrayCollection();
        $this->subjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

 
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Sujet>
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Sujet $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects[] = $subject;
            $subject->setUser($this);
        }

        return $this;
    }

    public function removeSubject(Sujet $subject): self
    {
        if ($this->subjects->removeElement($subject)) {
            if ($subject->getUser() === $this) {
                $subject->setUser(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
    public function __toString()
    {
        return $this->avatar;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaries(): Collection
    {
        return $this->commentaries;
    }

    public function addCommentaries(Commentaire $commentaire): self
    {
        if (!$this->commentaries->contains($commentaire)) {
            $this->commentaries[] = $commentaire;
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaries->removeElement($commentaire)) {
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(?string $signature): self
    {
        $this->signature = $signature;

        return $this;
    }
}
