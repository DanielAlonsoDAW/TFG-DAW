<?php
class Como_Funciona extends Controlador
{
    public function __construct()
    {
        session_start();
    }
    public function index()
    {

        $this->vista('como_funciona/inicio');
    }
}
