<?php

namespace App\Entity;

use App\Repository\PorteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PorteurRepository::class)]
class Porteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Descrizione = null;

    #[ORM\Column(length: 255)]
    private ?string $PIN = null;

    #[ORM\Column]
    private ?bool $Obsoleto = null;

    /**
     * @var Collection<int, Prenotazione>
     */
    #[ORM\OneToMany(targetEntity: Prenotazione::class, mappedBy: 'fkPorteur', orphanRemoval: true)]
    private Collection $Prenotazioni;

    public function __construct()
    {
        $this->Prenotazioni = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescrizione(): ?string
    {
        return $this->Descrizione;
    }

    public function setDescrizione(string $Descrizione): static
    {
        $this->Descrizione = $Descrizione;

        return $this;
    }

    public function getPIN(): ?string
    {
        return $this->PIN;
    }

    public function setPIN(string $PIN): static
    {
        $this->PIN = $PIN;

        return $this;
    }

    public function isObsoleto(): ?bool
    {
        return $this->Obsoleto;
    }

    public function setObsoleto(bool $Obsoleto): static
    {
        $this->Obsoleto = $Obsoleto;

        return $this;
    }

    /**
     * @return Collection<int, Prenotazione>
     */
    public function getPrenotazioni(): Collection
    {
        return $this->Prenotazioni;
    }

    public function addPrenotazioni(Prenotazione $prenotazioni): static
    {
        if (!$this->Prenotazioni->contains($prenotazioni)) {
            $this->Prenotazioni->add($prenotazioni);
            $prenotazioni->setFkPorteur($this);
        }

        return $this;
    }

    public function removePrenotazioni(Prenotazione $prenotazioni): static
    {
        if ($this->Prenotazioni->removeElement($prenotazioni)) {
            // set the owning side to null (unless already changed)
            if ($prenotazioni->getFkPorteur() === $this) {
                $prenotazioni->setFkPorteur(null);
            }
        }

        return $this;
    }
}
