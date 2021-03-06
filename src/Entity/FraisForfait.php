<?php

namespace App\Entity;

use App\Repository\FraisForfaitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FraisForfaitRepository::class)
 */
class FraisForfait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ORM\OneToOne(targetEntity="App\Entity\FicheFrais")
     * @ORM\JoinColumn(name="FicheFrais_id", referencedColumnName="id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /** 
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */

    private $repasMidi;

     /** 
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */

    private $nuit;

    /** 
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */

    private $etape;

    /** 
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */

    private $km;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $proprietaire;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $mois;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $etat;


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

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getrepasMidi(): ?int
    {
        return $this->repasMidi;
    }

    public function setrepasMidi(int $repasMidi): self
    {
        $this->repasMidi = $repasMidi;

        return $this;
    }

    public function getnuit(): ?int
    {
        return $this->nuit;
    }

    public function setnuit(int $nuit): self
    {
        $this->nuit = $nuit;

        return $this;
    }

    public function getetape(): ?int
    {
        return $this->etape;
    }

    public function setetape(int $etape): self
    {
        $this->etape = $etape;

        return $this;
    }

    public function getkm(): ?int
    {
        return $this->km;
    }

    public function setkm(int $km): self
    {
        $this->km = $km;

        return $this;
    }

    public function getProprietaire(): ?string
    {
        return $this->proprietaire;
    }

    public function setProprietaire(string $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
