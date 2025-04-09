<?php

class Home_Model
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }
    function idioma($language)
    {
        $this->db->query("SELECT $language from idiomas");

        return $this->db->registros();
    }
}
