<?php

final class Mailer
{
    public function send(string $to, string $subject, string $html): void
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: no-reply@meusite.com\r\n";

        if (!mail($to, $subject, $html, $headers)) {
            throw new Exception("Falha ao enviar email.");
        }
    }
}
