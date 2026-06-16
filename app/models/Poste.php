<?php

class Poste implements JsonSerializable
{
    private int $id;
    private int $id_freguesia;
    private int $id_estado;
    private string $longitude;
    private string $latitude;
    private string $observacao;

    public function __construct(int $id, int $id_freguesia, int $id_estado, string $longitude, string $latitude, string $observacao = '')
    {
        $this->id = $id;
        $this->id_freguesia = $id_freguesia;
        $this->id_estado = $id_estado;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->observacao = $observacao;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'id_freguesia' => $this->id_freguesia,
            'id_estado' => $this->id_estado,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'observacao' => $this->observacao,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdFreguesia(): int
    {
        return $this->id_freguesia;
    }
    public function setIdFreguesia(int $id_freguesia): void
    {
        $this->id_freguesia = $id_freguesia;
    }

    public function getIdEstado(): int
    {
        return $this->id_estado;
    }
    public function setIdEstado(int $id_estado): void
    {
        $this->id_estado = $id_estado;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }
    public function setLongitude(string $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }
    public function setLatitude(string $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getObservacao(): string
    {
        return $this->observacao;
    }
    public function setObservacao(string $observacao): void
    {
        $this->observacao = $observacao;
    }
}