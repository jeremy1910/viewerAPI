<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfilRepository")
 */
class Profil
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     * @Groups({"badge", "profil", "glecteur"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil"})
     * @Groups({"badge", "profil", "glecteur"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"badge", "profil", "glecteur"})
     */
    private $Description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"badge", "profil"})
     */
    private $Extension;


    /**
     * @ORM\OneToMany(targetEntity="ProfilGlecteurVariable", mappedBy="profil")
     * @Groups({"profil"})
     */
    private $profilGlecteurVariable;

    /**
     * @ORM\ManyToMany(targetEntity="Badge", mappedBy="profil")
     */
    private $badge;

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

        $this->profilGlecteurVariable = new ArrayCollection();
        $this->badge = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->Extension;
    }

    public function setExtension(?string $Extension): self
    {
        $this->Extension = $Extension;

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

    /**
     * @return mixed
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * @param mixed $badge
     * @return Profil
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;
        return $this;
    }


    public function addBadge(Badge $badge){
        if (!$this->badge->contains($badge))
        {
            $this->badge->add($badge);
        }
    }

    public function removeProfil(Badge $badge){
        if ($this->badge->contains($badge))
        {
            $this->badge->remove($badge);
        }
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
     * @return Profil
     */
    public function setAppID($appID)
    {
        $this->appID = $appID;
        return $this;
    }


}
