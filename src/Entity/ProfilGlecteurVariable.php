<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfilGlecteurVariablephRepository")
 */
class ProfilGlecteurVariable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @ORM\ManyToOne(targetEntity="Profil", inversedBy="profilGlecteurVariable")
     * @ORM\JoinColumn(name="profil_id", referencedColumnName="id", nullable=false)
     * @Groups({"glecteur"})
     */
    private $profil;

    /**
     * @ORM\ManyToOne(targetEntity="Glecteur", inversedBy="profilGlecteurVariable")
     * @ORM\JoinColumn(name="glecteur_id", referencedColumnName="id", nullable=false)
     * @Groups({"profil", "variable"})
     */
    private $glecteur;

    /**
     * @ORM\ManyToOne(targetEntity="Variable", inversedBy="profilGlecteurVariable")
     * @ORM\JoinColumn(name="variable_id", referencedColumnName="id", nullable=false)
     * @Groups({"profil"})
     */
    private $variable;

    /**
     * @return mixed
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * @param mixed $profil
     * @return ProfilGlecteurVariable
     */
    public function setProfil($profil)
    {
        $this->profil = $profil;
        return $this;
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
     * @return ProfilGlecteurVariable
     */
    public function setGlecteur($glecteur)
    {
        $this->glecteur = $glecteur;
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
     * @return ProfilGlecteurVariable
     */
    public function setVariable($variable)
    {
        $this->variable = $variable;
        return $this;
    }




}
