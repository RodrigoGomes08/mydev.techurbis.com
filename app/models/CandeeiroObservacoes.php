<?php
Class CandeeiroObservacoes implements JsonSerializable
{
    private $id;
    private $id_candeeiro;
    private $texto;
    private $created_at;

    public function __construct($id, $id_candeeiro, $texto, $created_at)
    {
        $this->id = $id;
        $this->id_candeeiro = $id_candeeiro;
        $this->texto = $texto;
        $this->created_at = $created_at;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'           => $this->id,
            'id_candeeiro' => $this->id_candeeiro,
            'texto'        => $this->texto,
            'created_at'   => $this->created_at
        ];
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    public function getIdCandeeiro() { return $this->id_candeeiro; }
    public function setIdCandeeiro($id_candeeiro) { $this->id_candeeiro = $id_candeeiro; }
    public function getTexto() { return $this->texto; }
    public function setTexto($texto) { $this->texto = $texto; }
    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
}