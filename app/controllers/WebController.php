<?php

class WebController{

    private function view($name){
        require __DIR__ . '/../../public/views/' . $name . '.php';
    }

    private function viewAdmin($nameAdmin){
        require __DIR__ . '/../../public/admin/views/' . $nameAdmin . '.php';
    }

    public function index(){
        $this->view('home');
    }

    public function login(){
        $this->view('login');
    }

    public function adminGeral(){
        //var_dump("Entrar na página admin");
        $this->viewAdmin('portalADMGeral');
    }

    public function adminContentores(){
        //var_dump("Entrar na página admin");
        $this->viewAdmin('portalADMContentores');
    }
    
    public function adminCidade(){
        //var_dump("Entrar na página admin");
        $this->viewAdmin('portalADMCidade');
    }

    public function adminParques(){
        //var_dump("Entrar na página admin");
        $this->viewAdmin('portalADMParques');
    }

    public function adminPostes(){
        //var_dump("Entrar na página admin");
        $this->viewAdmin('portalADMPostes');
    }

    public function adminUtilizadores(){
        //var_dump("Entrar na página admin");
        $this->viewAdmin('portalADMUtilizadores');
    }
}