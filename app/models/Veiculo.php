<?php
class Veiculo
{
    private int $id;
    private int $id_user;
    private string $tipo;
    private string $matricula;
    private string $modelo;
    private string $marca;
    private string $cor;
    private int $is_eletric;

    public function __construct(
        int $id,
        int $id_user,
        string $tipo,
        string $matricula,
        string $modelo,
        string $marca,
        string $cor,
        int $is_eletric,
    ) {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->tipo = $tipo;
        $this->matricula = $matricula;
        $this->modelo = $modelo;
        $this->marca = $marca;
        $this->cor = $cor;
        $this->is_eletric = $is_eletric;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdUser(): int
    {
        return $this->id_user;
    }
    public function setIdUser(int $id_user): void
    {
        $this->id_user = $id_user;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }
    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function getMatricula(): string
    {
        return $this->matricula;
    }
    public function setMatricula(string $matricula): void
    {
        $this->matricula = $matricula;
    }

    public function getModelo(): string
    {
        return $this->modelo;
    }
    public function setModelo(string $modelo): void
    {
        $this->modelo = $modelo;
    }

    public function getMarca(): string
    {
        return $this->marca;
    }
    public function setMarca(string $marca): void
    {
        $this->marca = $marca;
    }

    public function getCor(): string
    {
        return $this->cor;
    }
    public function setCor(string $cor): void
    {
        $this->cor = $cor;
    }

    public function getIsEletric(): int
    {
        return $this->is_eletric;
    }
    public function setIsEletric(int $is_eletric): void
    {
        $this->is_eletric = $is_eletric;
    }
}