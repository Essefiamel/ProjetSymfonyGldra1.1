<?php

namespace App\Entity;

use App\Repository\TuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TuteurRepository::class)
 */
class Tuteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $specialite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grade;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $titulaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $diplome;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;



    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="tuteurs")
     */
    private $Niveau;




    /**
     * @ORM\ManyToMany(targetEntity=Matiere::class, inversedBy="tuteurs")
     */
    private $Matiere;

    /**
     * @ORM\ManyToMany(targetEntity=Matiere::class, mappedBy="Tuteur")
     */
    private $matieres;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="Tuteur")
     */
    private $sections;

    public function __construct()
    {
        $this->Niveau = new ArrayCollection();
        $this->Matiere = new ArrayCollection();
        $this->matieres = new ArrayCollection();
        $this->sections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getTitulaire(): ?bool
    {
        return $this->titulaire;
    }

    public function setTitulaire(?bool $titulaire): self
    {
        $this->titulaire = $titulaire;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(?string $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): self
    {
        $this->User = $User;

        return $this;
    }

    

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveau(): Collection
    {
        return $this->Niveau;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->Niveau->contains($niveau)) {
            $this->Niveau[] = $niveau;
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        $this->Niveau->removeElement($niveau);

        return $this;
    }



    /**
     * @return Collection|Matiere[]
     */
    public function getMatiere(): Collection
    {
        return $this->Matiere;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->Matiere->contains($matiere)) {
            $this->Matiere[] = $matiere;
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        $this->Matiere->removeElement($matiere);

        return $this;
    }

    /**
     * @return Collection|Matiere[]
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setTuteur($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getTuteur() === $this) {
                $section->setTuteur(null);
            }
        }

        return $this;
    }
}
