<?php

require_once __DIR__ . '/../dao/EstadoDAO.php';

class EstadoController
{
    private function view($name, $data = [])
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../../public/admin/views/' . $name . '.php';
    }

    public function showPortalADMEstados()
    {
        if (empty($_SESSION['token'])) {
            header("Location: /login");
            exit;
        }

        $estadoDAO = new EstadoDAO();
        $estados = $estadoDAO->getAllEstados();

        $this->view('portalADMEstados', [
            'estados' => $estados
        ]);
    }
}