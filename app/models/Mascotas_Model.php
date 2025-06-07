<?php
// Modelo para gestionar las mascotas en la base de datos
class Mascotas_Model
{
    private $db;

    // Constructor: inicializa la conexión a la base de datos
    public function __construct()
    {
        $this->db = new DataBase();
    }

    // Obtiene todas las mascotas de un propietario según su tipo e id
    public function obtenerMascotas($propietario_tipo, $propietario_id)
    {
        $sql = "SELECT id, nombre, tipo, raza, edad, tamano, observaciones
            FROM patitas_mascotas
            WHERE propietario_tipo = :tipo
              AND propietario_id   = :id";
        $this->db->query($sql);
        $this->db->bind(':tipo', $propietario_tipo);
        $this->db->bind(':id', $propietario_id);
        return $this->db->registros();
    }

    // Obtiene los datos de una mascota por su id
    public function obtenerMascotaPorId($id)
    {
        $this->db->query("SELECT id, nombre, tipo, raza, edad, tamano, observaciones FROM patitas_mascotas WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    // Obtiene las imágenes asociadas a una mascota
    public function obtenerImagenes($mascota_id)
    {
        $sql = "SELECT id, imagen 
            FROM patitas_mascotas_imagenes
            WHERE mascota_id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $mascota_id);
        return $this->db->registros();
    }

    // Inserta una nueva mascota en la base de datos y devuelve su id
    public function insertarMascota($datos)
    {
        $this->db->query("
            INSERT INTO patitas_mascotas
              (nombre, tipo, raza, edad, tamano, observaciones, propietario_tipo, propietario_id)
            VALUES
              (:nombre, :tipo, :raza, :edad, :tamano, :observaciones, :ptipo, :pid)
        ");
        $this->db->bind(':nombre',         $datos['nombre']);
        $this->db->bind(':tipo',           $datos['tipo']);
        $this->db->bind(':raza',           $datos['raza']);
        $this->db->bind(':edad',           $datos['edad']);
        $this->db->bind(':tamano',         $datos['tamano']);
        $this->db->bind(':observaciones',  $datos['observaciones']);
        $this->db->bind(':ptipo',          $datos['propietario_tipo']);
        $this->db->bind(':pid',            $datos['propietario_id']);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    // Inserta una imagen asociada a una mascota
    public function insertarImagen($mascota_id, $ruta)
    {
        $this->db->query("
            INSERT INTO patitas_mascotas_imagenes (mascota_id, imagen)
            VALUES (:mid, :img)
        ");
        $this->db->bind(':mid', $mascota_id);
        $this->db->bind(':img', $ruta);
        return $this->db->execute();
    }

    // Actualiza los datos de una mascota existente
    public function actualizarMascota($datos)
    {
        $this->db->query("UPDATE patitas_mascotas 
        SET nombre = :nombre, tipo = :tipo, raza = :raza, edad = :edad, 
            tamano = :tamano, observaciones = :observaciones
        WHERE id = :id");

        $this->db->bind(':id', $datos['id']);
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':tipo', $datos['tipo']);
        $this->db->bind(':raza', $datos['raza']);
        $this->db->bind(':edad', $datos['edad']);
        $this->db->bind(':tamano', $datos['tamano']);
        $this->db->bind(':observaciones', $datos['observaciones']);

        return $this->db->execute();
    }

    // Elimina una mascota por su id
    public function eliminarMascota($id)
    {
        $this->db->query("DELETE FROM patitas_mascotas WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Verifica si las mascotas pertenecen al propietario indicado
    public function mascotasValidas($mascotas, $propietario_id)
    {
        if (empty($mascotas)) {
            return false;
        }

        $placeholders = implode(',', array_fill(0, count($mascotas), '?'));
        $sql = "SELECT id FROM patitas_mascotas WHERE propietario_id = ? AND id IN ($placeholders)";
        $this->db->query($sql);

        $this->db->bind(1, $propietario_id);

        foreach ($mascotas as $index => $mascota) {
            $this->db->bind($index + 2, $mascota);
        }

        return count($this->db->registros()) === count($mascotas);
    }

    // Obtiene los nombres de las mascotas a partir de un array de IDs.
    public function nombresMascotas($ids)
    {
        // Verifica que $ids sea un array no vacío
        if (!is_array($ids) || empty($ids)) {
            return [];
        }

        // Prepara los marcadores de posición para la consulta SQL
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        // Consulta los nombres de las mascotas cuyos IDs están en $ids
        $this->db->query("SELECT id, nombre FROM patitas_mascotas WHERE id IN ($placeholders)");

        // Asocia cada id en el orden de los placeholders
        foreach (array_values($ids) as $k => $id) {
            $this->db->bind($k + 1, $id);
        }

        // Devuelve un array de arrays asociativos con 'id' y 'nombre'
        return $this->db->registros();
    }

    // Obtiene los tipos y tamaños de un conjunto de mascotas a partir de sus IDs.
    public function obtenerTiposYTamanosMascotas($mascotas_ids)
    {
        // Verifica que el array no esté vacío y sea un array válido
        if (!is_array($mascotas_ids) || empty($mascotas_ids)) {
            return [];
        }

        // Prepara los marcadores de posición para la consulta SQL
        $placeholders = implode(',', array_fill(0, count($mascotas_ids), '?'));

        // Consulta los campos id, tipo y tamano de las mascotas cuyos IDs están en $mascotas_ids
        $this->db->query("SELECT id, tipo, tamano FROM patitas_mascotas WHERE id IN ($placeholders)");

        // Asocia cada id en el orden de los placeholders
        foreach (array_values($mascotas_ids) as $k => $id) {
            $this->db->bind($k + 1, $id);
        }

        // Devuelve un array de arrays asociativos con 'id', 'tipo' y 'tamano'
        return $this->db->registros();
    }
}
