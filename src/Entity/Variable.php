<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VariableRepository")
 */
class Variable
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     * @Groups({"glecteur", "profil", "variable"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"glecteur", "profil", "variable"})
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"glecteur", "profil", "variable"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Glecteur", mappedBy="Variable")
     * @Groups({"variable"})
     */
    private $glecteurs;

    /**
     * @ORM\OneToMany(targetEntity="ProfilGlecteurVariable", mappedBy="variable")
     * @Groups({"variable"})
     */
    private $profilGlecteurVariable;

    /**
     * @ORM\Column(type="string", length=1000,  nullable=true)
     * @Groups({"profil", "variable"})
     */
    private $extension;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Groups({"profil"})
     */
    private $translatedPH;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Installation")
     */
    private $installation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $appID;


    public function __construct()
    {
        $this->glecteurs = new ArrayCollection();
        $this->profilGlecteurVariable = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getTranslatedPH()
    {
        return $this->translatedPH;
    }

    /**
     * @param mixed $translatedPH
     * @return Variable
     */
    public function setTranslatedPH($translatedPH)
    {
        $this->translatedPH = $translatedPH;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     * @return Variable
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }


    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Glecteur[]
     */
    public function getGlecteurs(): Collection
    {
        return $this->glecteurs;
    }

    public function addGlecteur(Glecteur $glecteur): self
    {
        if (!$this->glecteurs->contains($glecteur)) {
            $this->glecteurs[] = $glecteur;
            $glecteur->addVariable($this);
        }

        return $this;
    }

    public function removeGlecteur(Glecteur $glecteur): self
    {
        if ($this->glecteurs->contains($glecteur)) {
            $this->glecteurs->removeElement($glecteur);
            $glecteur->removeVariable($this);
        }

        return $this;
    }


    /**
     * @return Collection|Variable[]
     */
    public function getProfilGlecteurVariable(): Collection
    {
        return $this->profilGlecteurVariable;
    }

    public function addProfilGlecteurVariable(ProfilGlecteurVariable $profilGlecteurVariable): self
    {
        if (!$this->profilGlecteurVariable->contains($profilGlecteurVariable)) {
            $this->profilGlecteurVariable[] = $profilGlecteurVariable;
        }

        return $this;
    }

    public function removeProfilGlecteurVariable(Variable $profilGlecteurVariable): self
    {
        if ($this->profilGlecteurVariable->contains($profilGlecteurVariable)) {
            $this->profilGlecteurVariable->removeElement($profilGlecteurVariable);
        }

        return $this;
    }

    public function getInstallation(): ?Installation
    {
        return $this->installation;
    }

    public function setInstallation(?Installation $installation): self
    {
        $this->installation = $installation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAppID()
    {
        return $this->appID;
    }

    /**
     * @param mixed $appID
     * @return Variable
     */
    public function setAppID($appID)
    {
        $this->appID = $appID;
        return $this;
    }


}
