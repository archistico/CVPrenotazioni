<?php

namespace App\Entity;

use App\Repository\PrenotazioneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrenotazioneRepository::class)]
class Prenotazione
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Prenotazioni')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Porteur $fkPorteur = null;

    #[ORM\Column(length: 255)]
    private ?string $Cliente = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $Dal = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $Al = null;

    #[ORM\Column]
    private ?int $PaxAdulti = null;

    #[ORM\Column]
    private ?int $PaxBambini = null;

    #[ORM\Column]
    private ?int $PaxAdolescenti = null;

    #[ORM\ManyToOne(inversedBy: 'Prenotazioni')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TipologiaOspitalita $fkTipologiaOspitalita = null;

    #[ORM\ManyToOne(inversedBy: 'Prenotazioni')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Albergo $fkAlbergo = null;

    #[ORM\ManyToOne(inversedBy: 'Prenotazioni')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TipologiaSistemazione $fkTipologiaSistemazione = null;

    #[ORM\ManyToOne(inversedBy: 'Prenotazioni')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tariffa $fkTariffa = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 14, scale: 2)]
    private ?string $Costo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Note = null;

    private ?string $Pin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFkPorteur(): ?Porteur
    {
        return $this->fkPorteur;
    }

    public function setFkPorteur(?Porteur $fkPorteur): static
    {
        $this->fkPorteur = $fkPorteur;

        return $this;
    }

    public function getCliente(): ?string
    {
        return $this->Cliente;
    }

    public function setCliente(string $Cliente): static
    {
        $this->Cliente = $Cliente;

        return $this;
    }

    public function getDal(): ?\DateTimeImmutable
    {
        return $this->Dal;
    }

    public function setDal(\DateTimeImmutable $Dal): static
    {
        $this->Dal = $Dal;

        return $this;
    }

    public function getAl(): ?\DateTimeImmutable
    {
        return $this->Al;
    }

    public function setAl(\DateTimeImmutable $Al): static
    {
        $this->Al = $Al;

        return $this;
    }

    public function getPaxAdulti(): ?int
    {
        return $this->PaxAdulti;
    }

    public function setPaxAdulti(int $PaxAdulti): static
    {
        $this->PaxAdulti = $PaxAdulti;

        return $this;
    }

    public function getPaxBambini(): ?int
    {
        return $this->PaxBambini;
    }

    public function setPaxBambini(int $PaxBambini): static
    {
        $this->PaxBambini = $PaxBambini;

        return $this;
    }

    public function getPaxAdolescenti(): ?int
    {
        return $this->PaxAdolescenti;
    }

    public function setPaxAdolescenti(int $PaxAdolescenti): static
    {
        $this->PaxAdolescenti = $PaxAdolescenti;

        return $this;
    }

    public function getFkTipologiaOspitalita(): ?TipologiaOspitalita
    {
        return $this->fkTipologiaOspitalita;
    }

    public function setFkTipologiaOspitalita(?TipologiaOspitalita $fkTipologiaOspitalita): static
    {
        $this->fkTipologiaOspitalita = $fkTipologiaOspitalita;

        return $this;
    }

    public function getFkAlbergo(): ?Albergo
    {
        return $this->fkAlbergo;
    }

    public function setFkAlbergo(?Albergo $fkAlbergo): static
    {
        $this->fkAlbergo = $fkAlbergo;

        return $this;
    }

    public function getFkTipologiaSistemazione(): ?TipologiaSistemazione
    {
        return $this->fkTipologiaSistemazione;
    }

    public function setFkTipologiaSistemazione(?TipologiaSistemazione $fkTipologiaSistemazione): static
    {
        $this->fkTipologiaSistemazione = $fkTipologiaSistemazione;

        return $this;
    }

    public function getFkTariffa(): ?Tariffa
    {
        return $this->fkTariffa;
    }

    public function setFkTariffa(?Tariffa $fkTariffa): static
    {
        $this->fkTariffa = $fkTariffa;

        return $this;
    }

    public function getCosto(): ?string
    {
        return $this->Costo;
    }

    public function setCosto(string $Costo): static
    {
        $this->Costo = $Costo;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->Note;
    }

    public function setNote(?string $Note): static
    {
        $this->Note = $Note;

        return $this;
    }

    public function getPin(): ?string
    {
        return $this->Pin;
    }

    public function setPin(?string $Pin): static
    {
        $this->Pin = $Pin;

        return $this;
    }
}
