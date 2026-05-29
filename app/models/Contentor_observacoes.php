<?php

Class Contentor_observacoes
{
    private $id;
    private $id_candeeiro;
    private $texto;

    public function __construct($id, $id_candeeiro, $texto)
    {
        $this->id = $id;
        $this->id_candeeiro = $id_candeeiro;
        $this->texto = $texto;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdCandeeiro() { return $this->id_candeeiro; }
    public function setIdCandeeiro($id_candeeiro) { $this->id_candeeiro = $id_candeeiro; }

    public function getTexto() { return $this->texto; }
    public function setTexto($texto) { $this->texto = $texto; }
}