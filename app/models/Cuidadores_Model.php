<?php

class Cuidadores_Model
{
    private $db;

    /**
     * Constructor de la clase. Inicializa la conexión a la base de datos.
     */
    public function __construct()
    {
        $this->db = new DataBase();
    }

    /**
     * Obtiene la lista de cuidadores con su nombre y ciudad.
     * @return array
     */
    public function obtenerCuidadores()
    {
        $this->db->query("SELECT nombre, ciudad FROM patitas_cuidadores");
        return $this->db->registros();
    }

    /**
     * Obtiene la contraseña de un cuidador por su ID.
     * @param int $id
     * @return mixed
     */
    public function obtenerContrasenaPorId($id)
    {
        $this->db->query("SELECT contrasena FROM patitas_cuidadores WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    /**
     * Obtiene cuidadores junto con la media de sus valoraciones.
     * @return array
     */
    public function obtenerCuidadoresConMedia()
    {
        $sql = "SELECT c.id, c.nombre, c.ciudad,
                       COALESCE(AVG(r.calificacion), 0) AS media_valoracion
                FROM patitas_cuidadores c
                LEFT JOIN patitas_resenas r ON c.id = r.cuidador_id
                GROUP BY c.id, c.nombre, c.ciudad";

        $this->db->query($sql);
        return $this->db->registros();
    }

    /**
     * Obtiene los 15 cuidadores mejor valorados.
     * @return array
     */
    public function obtenerCuidadoresTOP()
    {
        $sql = "SELECT  c.id, c.nombre, c.ciudad, c.pais,c.descripcion, c.imagen,
                    COALESCE(AVG(r.calificacion), 0) AS promedio_calificacion,COUNT(r.id) AS total_resenas
                    FROM patitas_cuidadores c
                    LEFT JOIN patitas_resenas r ON c.id = r.cuidador_id
                    GROUP BY c.id, c.nombre, c.ciudad, c.pais, c.descripcion, c.imagen
                    ORDER BY promedio_calificacion DESC LIMIT 15";

        $this->db->query($sql);
        return $this->db->registros();
    }

    /**
     * Obtiene el perfil completo de un cuidador por su ID.
     * @param int $id
     * @return mixed
     */
    public function obtenerPerfilCuidador($id)
    {
        $sql = "SELECT c.id, c.nombre, c.email, c.direccion, c.ciudad, c.pais, 
               c.descripcion, c.max_mascotas_dia, c.imagen, c.fecha_registro,
               AVG(r.calificacion) AS promedio_calificacion
        FROM patitas_cuidadores c
        LEFT JOIN patitas_resenas r ON c.id = r.cuidador_id
        WHERE c.id = :id
        GROUP BY c.id, c.nombre, c.email, c.direccion, c.ciudad, c.pais, 
                 c.descripcion, c.max_mascotas_dia, c.imagen, c.fecha_registro";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    /**
     * Obtiene los servicios ofrecidos por un cuidador.
     * @param int $id
     * @return array
     */
    public function obtenerServicios($id)
    {
        $sql = "SELECT servicio, precio
            FROM patitas_cuidador_servicios
            WHERE cuidador_id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->registros();
    }

    /**
     * Obtiene los tipos de mascotas admitidas por un cuidador.
     * @param int $id
     * @return array
     */
    public function obtenerTiposMascotas($id)
    {
        $sql = "SELECT tipo_mascota, tamano
            FROM patitas_cuidador_admite
            WHERE cuidador_id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->registros();
    }

    /**
     * Obtiene las mascotas asociadas a un cuidador.
     * @param int $id
     * @return array
     */
    public function obtenerMascotasCuidador($id)
    {
        $sql = "SELECT m.id, m.nombre, m.tipo, m.raza, m.edad, m.tamano, m.observaciones,
                   (SELECT imagen FROM patitas_mascotas_imagenes WHERE mascota_id = m.id LIMIT 1) AS imagen
            FROM patitas_mascotas m
            WHERE m.propietario_tipo = 'cuidador' AND m.propietario_id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->registros();
    }

    /**
     * Actualiza los datos de acceso (email, contraseña) de un cuidador.
     * @param array $datos
     * @return bool
     */
    public function actualizarAccesos($datos)
    {
        $this->db->query("UPDATE patitas_cuidadores 
        SET email = :email, contrasena = :contrasena
        WHERE id = :id");

        foreach ($datos as $campo => $valor) {
            $this->db->bind(":$campo", $valor);
        }

        return $this->db->execute();
    }

    /**
     * Actualiza los datos de acceso (email, contraseña) de un cuidador.
     * @param array $datos
     * @return bool
     */
    public function actualizarEmail($datos)
    {
        $this->db->query("UPDATE patitas_cuidadores 
        SET email = :email
        WHERE id = :id");

        foreach ($datos as $campo => $valor) {
            $this->db->bind(":$campo", $valor);
        }

        return $this->db->execute();
    }

    /**
     * Actualiza los datos personales de un cuidador.
     * @param array $datos
     * @return bool
     */
    public function actualizarDatosCuidador($datos)
    {
        $this->db->query("UPDATE patitas_cuidadores 
        SET nombre = :nombre, direccion = :direccion, ciudad = :ciudad, 
            pais = :pais, imagen = :imagen, lat = :lat, lng = :lng
        WHERE id = :id");

        foreach ($datos as $campo => $valor) {
            $this->db->bind(":$campo", $valor);
        }

        return $this->db->execute();
    }

    /**
     * Elimina los tipos de mascotas admitidas por un cuidador.
     * @param int $id
     * @return bool
     */
    public function eliminarAdmiteMascotas($id)
    {
        $this->db->query("DELETE FROM patitas_cuidador_admite WHERE cuidador_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Inserta un tipo de mascota admitida para un cuidador.
     * @param int $id
     * @param string $tipo
     * @param string $tamano
     * @return bool
     */
    public function insertarTipoMascota($id, $tipo, $tamano)
    {
        $this->db->query("INSERT INTO patitas_cuidador_admite (cuidador_id, tipo_mascota, tamano)
                      VALUES (:id, :tipo, :tamano)");
        $this->db->bind(':id', $id);
        $this->db->bind(':tipo', $tipo);
        $this->db->bind(':tamano', $tamano);
        return $this->db->execute();
    }

    /**
     * Actualiza el número máximo de mascotas que puede cuidar un cuidador al día.
     * @param int $id
     * @param int $max_mascotas_dia
     * @return bool
     */
    public function actualizarMaxMascotas($id, $max_mascotas_dia)
    {
        $this->db->query("UPDATE patitas_cuidadores 
        SET max_mascotas_dia = :max_mascotas_dia
        WHERE id = :id");

        $this->db->bind(':id', $id);
        $this->db->bind(':max_mascotas_dia', $max_mascotas_dia);

        return $this->db->execute();
    }

    /**
     * Elimina los servicios ofrecidos por un cuidador.
     * @param int $id
     * @return bool
     */
    public function eliminarServicios($id)
    {
        $this->db->query("DELETE FROM patitas_cuidador_servicios WHERE cuidador_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Inserta un servicio ofrecido por un cuidador.
     * @param int $id
     * @param string $servicio
     * @param float $precio
     * @return bool
     */
    public function insertarServicio($id, $servicio, $precio)
    {
        $this->db->query("INSERT INTO patitas_cuidador_servicios (cuidador_id, servicio, precio)
                      VALUES (:id, :servicio, :precio)");
        $this->db->bind(':id', $id);
        $this->db->bind(':servicio', $servicio);
        $this->db->bind(':precio', $precio);
        return $this->db->execute();
    }

    /**
     * Obtiene los datos de un cuidador a partir del ID de una reserva.
     * @param int $reserva_id
     * @return mixed
     */
    public function obtenerCuidadorPorReservaId($reserva_id)
    {
        $sql = "SELECT c.id, c.nombre, c.email, c.direccion, c.ciudad, c.pais, 
                c.descripcion, c.max_mascotas_dia, c.imagen, c.fecha_registro
                FROM patitas_cuidadores c
                JOIN patitas_reservas r ON c.id = r.cuidador_id
                WHERE r.id = :reserva_id";
        $this->db->query($sql);
        $this->db->bind(':reserva_id', $reserva_id);
        return $this->db->registro();
    }
}
