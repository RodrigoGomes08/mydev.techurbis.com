<?php

class ContentorObservacoes implements JsonSerializable
{
    private $id;
    private $id_contentor;
    private $texto;
    private $created_at;

    public function __construct($id, $id_contentor, $texto, $created_at = null)
    {
        $this->id = $id;
        $this->id_contentor = $id_contentor;
        $this->texto = $texto;
        $this->created_at = $created_at;
    }



    public function jsonSerialize(): array
    {
        return [
            'id'           => $this->id,
            'id_contentor' => $this->id_contentor,
            'texto'        => $this->texto,
            'created_at'   => $this->created_at
        ];
    }


    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdContentor() { return $this->id_contentor; }
    public function setIdContentor($id_contentor) { $this->id_contentor = $id_contentor; }

    public function getTexto() { return $this->texto; }
    public function setTexto($texto) { $this->texto = $texto; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
}