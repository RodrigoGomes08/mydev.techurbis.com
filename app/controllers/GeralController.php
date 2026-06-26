<?php

class GeralController
{

    private function view($name, $data = [])
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../../public/admin/views/' . $name . '.php';
    }

    public function showPortalADMGeral()
    {
        if (empty($_SESSION['token'])) {
            header("Location: /login");
            exit;
        }

        $userDAO = new UserDAO();
        $numTotalUsers = $userDAO->numTotalUsers();

        $contentorDAO = new ContentorDAO();
        $contentoresCriticos = $contentorDAO->numTotalContentoresCriticos();
        $estadoContentores = $contentorDAO->estadoSistemaContentores();

        $posteDAO = new PosteDAO();
        $postesAvariados = $posteDAO->numTotalPostesAvariados();
        $estadoPostes = $posteDAO->estadoSistemaPostes();

        $parqueDAO = new ParqueDAO();
        $ocupacaoMediaDosParques = $parqueDAO->ocupacaoMediaDosParques();
        $estadoParques = $parqueDAO->estadoSistemaParques();

        $this->view('portalADMGeral', [
            'numTotalUsers' => $numTotalUsers,
            'contentoresCriticos' => $contentoresCriticos,
            'postesAvariados' => $postesAvariados,
            'ocupacaoMediaDosParques' => $ocupacaoMediaDosParques,
            'estadoContentores' => $estadoContentores,
            'estadoPostes' => $estadoPostes,
            'estadoParques' => $estadoParques,
        ]);
    }
}