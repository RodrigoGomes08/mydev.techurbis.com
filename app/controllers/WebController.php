<?php

class WebController{

    private function view($name){
        require __DIR__ . '/../../public/views/' . $name . '.php';
    }

    public function index(){
        $this->view('home');
    }
}