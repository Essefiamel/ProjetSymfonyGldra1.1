<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatiereRepository::class)
 */
class Matiere
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
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $optionelle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Tuteur::class, mappedBy="Matiere")
     */
    private $tuteurs;

    /**
     * @ORM\ManyToMany(targetEntity=Tuteur::class, inversedBy="matieres")
     */
    private $Tuteur;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="matieres")
     */
    private $Niveau;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, mappedBy="Matiere")
     */
    private $niveaux;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="Matiere")
     */
    private $sections;


    public function __construct()
    {
        $this->tuteurs = new ArrayCollection();
        $this->Tuteur = new ArrayCollection();
        $this->Niveau = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->sections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getOptionelle(): ?bool
    {
        return $this->optionelle;
    }

    public function setOptionelle(bool $optionelle): self
    {
        $this->optionelle = $optionelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Tuteur[]
     */
    public function getTuteurs(): Collection
    {
        return $this->tuteurs;
    }

    public function addTuteur(Tuteur $tuteur): self
    {
        if (!$this->tuteurs->contains($tuteur)) {
            $this->tuteurs[] = $tuteur;
            $tuteur->addMatiere($this);
        }

        return $this;
    }

    public function removeTuteur(Tuteur $tuteur): self
    {
        if ($this->tuteurs->removeElement($tuteur)) {
            $tuteur->removeMatiere($this);
        }

        return $this;
    }

    /**
     * @return Collection|Tuteur[]
     */
    public function getTuteur(): Collection
    {
        return $this->Tuteur;
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
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
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
            $section->setMatiere($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getMatiere() === $this) {
                $section->setMatiere(null);
            }
        }

        return $this;
    }


}
