<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\GlecteurRepository")
 */
class Glecteur
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     * @Groups({"profil", "glecteur", "variable", "badgeGlecteurVariable", "badge"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil", "glecteur", "variable", "badge", "badgeGlecteurVariable"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil", "glecteur", "variable", "badge", "badgeGlecteurVariable"})
     */
    private $Description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"profil"})
     */
    private $Extention;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Variable", inversedBy="glecteurs", cascade={"remove", "persist"})
     * @Groups({"glecteur"})
     *
     */
    private $Variable;


    /**
     * @ORM\OneToMany(targetEntity="ProfilGlecteurVariable", mappedBy="glecteur", cascade={"remove"}, cascade={"remove", "persist"})
     * @Groups({"glecteur"})
     */
    private $profilGlecteurVariable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Installation")
     */
    private $installation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $appID;

	/**
	 * @ORM\OneToMany(targetEntity="\App\Entity\BadgeGlecteurVariable", mappedBy="badge", cascade={"remove"})
	 */
	private $badgeGlecteurVariable;

    public function __construct()
    {
        $this->Variable = new ArrayCollection();
        $this->profilGlecteurVariable = new ArrayCollection();
		$this->badgeGlecteurVariable = new ArrayCollection();
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

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getExtention(): ?int
    {
        return $this->Extention;
    }

    public function setExtention(?int $Extention): self
    {
        $this->Extention = $Extention;

        return $this;
    }

    /**
     * @return Collection|Variable[]
     */
    public function getVariable(): Collection
    {
        return $this->Variable;
    }

    public function addVariable(Variable $variable): self
    {
        if (!$this->Variable->contains($variable)) {
            $this->Variable[] = $variable;
        }

        return $this;
    }

    public function removeVariable(Variable $variable): self
    {
        if ($this->Variable->contains($variable)) {
            $this->Variable->removeElement($variable);
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
     * @return Glecteur
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
	 * @return Glecteur
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
