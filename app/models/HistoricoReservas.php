<?php

class HistoricoReservas
{
    private int $id;
    private int $id_lugar;
    private int $id_veiculo;
    private DateTime $reserved_from;
    private DateTime $reserved_until;

    public function __construct(
        int $id,
        int $id_lugar,
        int $id_veiculo,
        DateTime $reserved_from,
        DateTime $reserved_until
    ) {
        $this->id = $id;
        $this->id_lugar = $id_lugar;
        $this->id_veiculo = $id_veiculo;
        $this->reserved_from = $reserved_from;
        $this->reserved_until = $reserved_until;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdLugar(): int
    {
        return $this->id_lugar;
    }

    public function setIdLugar(int $id_lugar): void
    {
        $this->id_lugar = $id_lugar;
    }

    public function getIdVeiculo(): int
    {
        return $this->id_veiculo;
    }

    public function setIdVeiculo(int $id_veiculo): void
    {
        $this->id_veiculo = $id_veiculo;
    }

    public function getReservedFrom(): DateTime
    {
        return $this->reserved_from;
    }

    public function setReservedFrom(DateTime $reserved_from): void
    {
        $this->reserved_from = $reserved_from;
    }

    public function getReservedUntil(): DateTime
    {
        return $this->reserved_until;
    }

    public function setReservedUntil(DateTime $reserved_until): void
    {
        $this->reserved_until = $reserved_until;
    }
}