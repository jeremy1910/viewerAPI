<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BadgeRepository")
 */
class Badge
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $nom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $operateurCreateur;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $dateDebVal;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $dateFinVal;

    /**
     * @ORM\Column(type="datetime",  nullable=true)
     * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $dateDebVal2;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $dateFinVal2;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $valide;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $code1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $prenom;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Profil", inversedBy="badge", cascade={"persist", "remove"})
     * @Groups({"badge"})
     */
    private $profil;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Installation")
     */
    private $installation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $appID;

	/**
	 * @ORM\OneToMany(targetEntity="\App\Entity\BadgeGlecteurVariable", mappedBy="badge")
	 * @Groups({"badge"})
	 */
    private $badgeGlecteurVariable;

    public function __construct()
    {
        $this->profil = new ArrayCollection();
        $this->badgeGlecteurVariable = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }



    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }


    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getOperateurCreateur(): ?int
    {
        return $this->operateurCreateur;
    }

    public function setOperateurCreateur(?int $operateurCreateur): self
    {
        $this->operateurCreateur = $operateurCreateur;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateDebVal(): ?\DateTimeInterface
    {
        return $this->dateDebVal;
    }

    public function setDateDebVal(?\DateTimeInterface $dateDebVal): self
    {
        $this->dateDebVal = $dateDebVal;

        return $this;
    }

    public function getDateFinVal(): ?\DateTimeInterface
    {
        return $this->dateFinVal;
    }

    public function setDateFinVal(?\DateTimeInterface $dateFinVal): self
    {
        $this->dateFinVal = $dateFinVal;

        return $this;
    }

    public function getDateDebVal2(): ?\DateTimeInterface
    {
        return $this->dateDebVal2;
    }

    public function setDateDebVal2(\DateTimeInterface $dateDebVal2): self
    {
        $this->dateDebVal2 = $dateDebVal2;

        return $this;
    }

    public function getDateFinVal2(): ?\DateTimeInterface
    {
        return $this->dateFinVal2;
    }

    public function setDateFinVal2(?\DateTimeInterface $dateFinVal2): self
    {
        $this->dateFinVal2 = $dateFinVal2;

        return $this;
    }

    public function getValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

    public function getCode1(): ?string
    {
        return $this->code1;
    }

    public function setCode1(?string $code1): self
    {
        $this->code1 = $code1;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfil(): Collection
    {
        return $this->profil;
    }

    /**
     * @param mixed $profil
     * @return Badge
     */
    public function setProfil($profil)
    {
        $this->profil = $profil;
        return $this;
    }


    public function addProfil(Profil $profil){
        if (!$this->profil->contains($profil))
        {
            $this->profil[] = $profil;
        }
        return $this;
    }

    public function removeProfil(Profil $profil){
        if ($this->profil->contains($profil))
        {
            $this->profil->removeElement($profil);
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
     * @return Badge
     */
    public function setAppID($appID)
    {
        $this->appID = $appID;
        return $this;
    }

	/**
	 * @return mixed
	 */
	public function getBadgeGlecteurVariable()
	{
		return $this->badgeGlecteurVariable;
	}

	/**
	 * @param mixed $badgeGlecteurVariable
	 * @return Badge
	 */
	public function setBadgeGlecteurVariable($badgeGlecteurVariable)
	{
		$this->badgeGlecteurVariable = $badgeGlecteurVariable;
		return $this;
	}



	public function addBadgeGlecteurVariable(BadgeGlecteurVariable $badgeGlecteurVariable){
		if (!$this->badgeGlecteurVariable->contains($badgeGlecteurVariable))
		{
			$this->badgeGlecteurVariable->add($badgeGlecteurVariable);
		}
	}

	public function removeBadgeGlecteurVariable(BadgeGlecteurVariable $badgeGlecteurVariable){
		if ($this->badgeGlecteurVariable->contains($badgeGlecteurVariable))
		{
			$this->badgeGlecteurVariable->remove($badgeGlecteurVariable);
		}
	}

}
