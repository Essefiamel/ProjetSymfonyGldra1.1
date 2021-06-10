<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields= {"email"},message= "L'email que vous avez indiqué est déja utilisé")
 * @UniqueEntity(fields= {"userName"},message= "le Nom utilisateur que vous avez indiqué est déja utilisé")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="date")
     */
    private $datenaissance;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userName;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="4",minMessage ="Votre mot de passe doit faire au minimum 4 caractères")
     * @Assert\EqualTo(propertyPath="confirm_password", message="Mot de passe et confirmation ne sont pas identiques")
     */
    private $password;
    public $confirm_password;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $actif;


    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];


    public function getRoles(): array
    {
        $roles = $this->roles;
        // garantit que chaque utilisateur possède le rôle ROLE_USER
        // équvalent à array_push() qui ajoute un élément au tabeau
        //$roles[] = 'ROLE_INVITE';
        $roles[] = null;
        //array_unique élémine des doublons
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(\DateTimeInterface $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
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

    public function setUserName($userName): void
    {
        $this->userName = $userName;
    }


    public function getUserName()
    {
        return $this->userName;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
    public function fullName() {
        return $this->nom . ' ' . $this->prenom ;
    }
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function eraseCredentials() {}
    public function getSalt() {}
    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }




}
