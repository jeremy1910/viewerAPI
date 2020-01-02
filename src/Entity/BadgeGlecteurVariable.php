<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BadgeGlecteurVariableRepository")
 */
class BadgeGlecteurVariable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
	 * @Groups({"badge", "badgeGlecteurVariable"})
     */
    private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Badge", inversedBy="badgeGlecteurVariable", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(name="badge_id", referencedColumnName="id", nullable=false)
	 * @Groups({"glecteur", "badgeGlecteurVariable"})
	 */
	private $badge;

	/**
	 * @ORM\ManyToOne(targetEntity="Glecteur", inversedBy="badgeGlecteurVariable", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(name="glecteur_id", referencedColumnName="id", nullable=false)
	 * @Groups({"profil", "variable", "badge", "badgeGlecteurVariable"})
	 */
	private $glecteur;

	/**
	 * @ORM\ManyToOne(targetEntity="Variable", inversedBy="badgeGlecteurVariable", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(name="variable_id", referencedColumnName="id", nullable=false)
	 * @Groups({"profil", "badge", "badgeGlecteurVariable"})
	 */
	private $variable;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Installation")
	 */
	private $installation ;

    public function getId(): ?int
    {
        return $this->id;
    }

	/**
	 * @return mixed
	 */
	public function getGlecteur()
	{
		return $this->glecteur;
	}

	/**
	 * @param mixed $glecteur
	 * @return BadgeGlecteurVariable
	 */
	public function setGlecteur($glecteur)
	{
		$this->glecteur = $glecteur;
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
	 * @return BadgeGlecteurVariable
	 */
	public function setBadge($badge)
	{
		$this->badge = $badge;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getVariable()
	{
		return $this->variable;
	}

	/**
	 * @param mixed $variable
	 * @return BadgeGlecteurVariable
	 */
	public function setVariable($variable)
	{
		$this->variable = $variable;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getInstallation()
	{
		return $this->installation;
	}

	/**
	 * @param mixed $installation
	 * @return BadgeGlecteurVariable
	 */
	public function setInstallation($installation)
	{
		$this->installation = $installation;
		return $this;
	}



}
