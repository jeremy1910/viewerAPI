<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstallationRepository")
 *
 */
class Installation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom ne peut être vide")
     */
    private $name;

	/**
	 * @ORM\Column(type="integer")
	 * @Assert\NotBlank(message="Le mode ne peut être vide")
	 * @Assert\Range(
	 *      min = 0,
	 *      max = 15,
	 *      minMessage = "Mode incorect doit être entre 0 et 15",
	 *      maxMessage = "Mode incorect doit être entre 0 et 15"
	 * )
	 */
    private $mode;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

	/**
	 * @return mixed
	 */
	public function getMode()
	{
		return $this->mode;
	}

	/**
	 * @param mixed $mode
	 * @return Installation
	 */
	public function setMode($mode)
	{
		$this->mode = $mode;
		return $this;
	}


}
