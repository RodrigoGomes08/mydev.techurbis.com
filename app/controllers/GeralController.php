<?php

class GeralController{

    private function view($name, $data = [])
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../../public/admin/views/' . $name . '.php';
    }

    public function showPortalADMUtilizadores()
    {
        if (empty($_SESSION['token'])) {
            header("Location: /login");
            exit;
        }

        $userDAO = new UserDAO();
        $numTotalusers = $userDAO->numTotalUsers();

        $contentorDAO = new ContentorDAO();
        $contentoresCriticos = $contentorDAO->numTotalContentoresCriticos();

        $this->view('portalADMUtilizadores', [
            'numTotalUsers' => $numTotalusers,
            'contentoresCriticos' => $contentoresCriticos,
        ]);
    }
}