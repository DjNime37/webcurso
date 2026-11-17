<?php
// Define la clase que gestiona las direcciones de los usuarios.
class Direcciones
{
    // Guarda la conexión PDO para utilizarla en las consultas SQL.
    private PDO $pdo;
    // Constructor: recibe la conexión con la base de datos.
    public function __construct(PDO $pdo)
    {
        // Guarda la conexión recibida en la propiedad de la clase.
        $this->pdo = $pdo;
    }
    /*
    |--------------------------------------------------------------------------
    | OBTENER TODAS LAS DIRECCIONES DEL USUARIO
    |--------------------------------------------------------------------------
    */
    // Recupera todas las direcciones que pertenecen a un usuario.
    public function obtenerDirecciones(int $usuarioId): array
    {
        // Selecciona los datos de las direcciones del usuario indicado.
        // ORDER BY id DESC muestra primero las direcciones con ID más reciente.
        $sql = "
            SELECT
                id,
                usuario_id,
                codigo_postal,
                nombre_via,
                numero,
                piso,
                puerta,
                pais,
                tipo,
                created_at,
                updated_at
            FROM direcciones
            WHERE usuario_id = :usuario_id
            ORDER BY id DESC
        ";
        // Prepara la consulta SQL para utilizar un parámetro seguro.
        $stmt = $this->pdo->prepare($sql);
        // Ejecuta la consulta con el identificador del usuario.
        $stmt->execute([
            ':usuario_id' => $usuarioId
        ]);
        // Devuelve todas las direcciones encontradas como un array asociativo.
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /*
    |--------------------------------------------------------------------------
    | OBTENER UNA DIRECCIÓN
    |--------------------------------------------------------------------------
    */
    // Recupera una dirección concreta comprobando que pertenezca al usuario.
    // Devuelve un array con sus datos o null si no existe.
    public function obtenerDireccion(
        int $direccionId,
        int $usuarioId
    ): ?array {
        // Selecciona los datos de la dirección solicitada.
        // Se comprueban tanto el ID de la dirección como el del usuario.
        // LIMIT 1 limita el resultado a una única fila.
        $sql = "
            SELECT
                id,
                usuario_id,
                codigo_postal,
                nombre_via,
                numero,
                piso,
                puerta,
                pais,
                tipo,
                created_at,
                updated_at
            FROM direcciones
            WHERE id = :id
            AND usuario_id = :usuario_id
            LIMIT 1
        ";
        // Prepara la consulta.
        $stmt = $this->pdo->prepare($sql);
        // Ejecuta la búsqueda con los identificadores recibidos.
        $stmt->execute([
            ':id' => $direccionId,
            ':usuario_id' => $usuarioId
        ]);
        // Recupera la dirección encontrada.
        $direccion = $stmt->fetch(PDO::FETCH_ASSOC);
        // Devuelve la dirección si existe; en caso contrario, devuelve null.
        return $direccion ?: null;
    }
    /*
    |--------------------------------------------------------------------------
    | CREAR UNA DIRECCIÓN
    |--------------------------------------------------------------------------
    | Devuelve el ID de la dirección recién creada.
    |--------------------------------------------------------------------------
    */
    // Inserta una nueva dirección en la base de datos.
    public function crearDireccion(
        int $usuarioId,
        string $codigoPostal,
        string $nombreVia,
        string $numero,
        string $piso,
        string $puerta,
        string $pais,
        string $tipo
    ): int {
        // Prepara la inserción de los datos de la dirección.
        // Los parámetros evitan incorporar directamente los valores a la consulta.
        $sql = "
            INSERT INTO direcciones
            (
                usuario_id,
                codigo_postal,
                nombre_via,
                numero,
                piso,
                puerta,
                pais,
                tipo
            )
            VALUES
            (
                :usuario_id,
                :codigo_postal,
                :nombre_via,
                :numero,
                :piso,
                :puerta,
                :pais,
                :tipo
            )
        ";
        // Prepara la consulta SQL.
        $stmt = $this->pdo->prepare($sql);
        // Ejecuta la inserción con los datos recibidos.
        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':codigo_postal' => $codigoPostal,
            ':nombre_via' => $nombreVia,
            ':numero' => $numero,
            ':piso' => $piso,
            ':puerta' => $puerta,
            ':pais' => $pais,
            ':tipo' => $tipo
        ]);
        // Devuelve el ID autoincremental de la dirección recién insertada.
        return (int) $this->pdo->lastInsertId();
    }
    /*
    |--------------------------------------------------------------------------
    | GUARDAR UNA COPIA EN EL HISTORIAL
    |--------------------------------------------------------------------------
    */
    // Guarda una copia de los datos de una dirección en el historial.
    // Devuelve true si la inserción se ejecuta correctamente.
    public function guardarHistorial(
        int $direccionId,
        int $usuarioId,
        string $codigoPostal,
        string $nombreVia,
        string $numero,
        string $piso,
        string $puerta,
        string $pais,
        string $tipo
    ): bool {
        // Inserta los datos de la dirección en la tabla de historial.
        // NOW() registra la fecha y hora actuales del servidor.
        $sql = "
        INSERT INTO direcciones_historial
        (
            direccion_id,
            usuario_id,
            codigo_postal,
            nombre_via,
            numero,
            piso,
            puerta,
            pais,
            tipo,
            created_at
        )
        VALUES
        (
            :direccion_id,
            :usuario_id,
            :codigo_postal,
            :nombre_via,
            :numero,
            :piso,
            :puerta,
            :pais,
            :tipo,
            NOW()
        )
    ";
        // Prepara la consulta SQL.
        $stmt = $this->pdo->prepare($sql);
        // Guarda la copia con los valores recibidos.
        return $stmt->execute([
            ':direccion_id' => $direccionId,
            ':usuario_id' => $usuarioId,
            ':codigo_postal' => $codigoPostal,
            ':nombre_via' => $nombreVia,
            ':numero' => $numero,
            ':piso' => $piso,
            ':puerta' => $puerta,
            ':pais' => $pais,
            ':tipo' => $tipo
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | OBTENER EL HISTORIAL DE UNA DIRECCIÓN
    |--------------------------------------------------------------------------
    */
    // Recupera los registros históricos de una dirección concreta.
    public function obtenerHistorial(
        int $direccionId,
        int $usuarioId
    ): array {
        // Selecciona los datos históricos de la dirección.
        // was_deleted permite consultar la información de eliminación
        // cuando ese dato está registrado en la tabla.
        // ORDER BY id ASC muestra primero los registros más antiguos.
        $sql = "
            SELECT
                id,
                direccion_id,
                codigo_postal,
                nombre_via,
                numero,
                piso,
                puerta,
                pais,
                tipo,
                created_at,
                was_deleted
            FROM direcciones_historial
            WHERE direccion_id = :direccion_id
            AND usuario_id = :usuario_id
            ORDER BY id ASC
        ";
        // Prepara la consulta SQL.
        $stmt = $this->pdo->prepare($sql);
        // Ejecuta la consulta limitándola a la dirección y al usuario indicados.
        $stmt->execute([
            ':direccion_id' => $direccionId,
            ':usuario_id' => $usuarioId
        ]);
        // Devuelve todas las entradas del historial encontradas.
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR UNA DIRECCIÓN
    |--------------------------------------------------------------------------
    */
    // Modifica los datos de una dirección existente.
    public function actualizarDireccion(
        int $direccionId,
        int $usuarioId,
        string $codigoPostal,
        string $nombreVia,
        string $numero,
        string $piso,
        string $puerta,
        string $pais,
        string $tipo
    ): bool {
        // Actualiza los datos de la dirección.
        // La condición WHERE comprueba el ID y el usuario propietario.
        // updated_at registra la fecha y hora de la modificación.
        $sql = "
        UPDATE direcciones
        SET
            codigo_postal = :codigo_postal,
            nombre_via = :nombre_via,
            numero = :numero,
            piso = :piso,
            puerta = :puerta,
            pais = :pais,
            tipo = :tipo,
            updated_at = NOW()
        WHERE id = :id
        AND usuario_id = :usuario_id
    ";
        // Prepara la consulta SQL.
        $stmt = $this->pdo->prepare($sql);
        // Ejecuta la actualización con los nuevos datos.
        // Devuelve true si la ejecución se realiza correctamente.
        return $stmt->execute([
            ':id' => $direccionId,
            ':usuario_id' => $usuarioId,
            ':codigo_postal' => $codigoPostal,
            ':nombre_via' => $nombreVia,
            ':numero' => $numero,
            ':piso' => $piso,
            ':puerta' => $puerta,
            ':pais' => $pais,
            ':tipo' => $tipo
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | ELIMINAR UNA DIRECCIÓN Y GUARDARLA EN EL HISTORIAL
    |--------------------------------------------------------------------------
    */
    // Elimina una dirección y registra una copia de sus datos en el historial.
    // Devuelve true si completa la operación y false si la dirección no existe.
    public function eliminarDireccion(
        int $direccionId,
        int $usuarioId
    ): bool {
        try {
            // Inicia una transacción para que el registro del historial
            // y la eliminación se completen como una única operación.
            $this->pdo->beginTransaction();
            // Obtiene los datos de la dirección antes de eliminarla.
            // También comprueba que pertenezca al usuario indicado.
            $direccion = $this->obtenerDireccion(
                $direccionId,
                $usuarioId
            );
            // Si la dirección no existe o no pertenece al usuario,
            // cancela la transacción y devuelve false.
            if (!$direccion) {
                $this->pdo->rollBack();
                return false;
            }
            // Guarda en el historial los datos de la dirección eliminada.
            // NOW() registra la fecha y hora de eliminación en was_deleted.
            // También registra la fecha de creación de esta entrada histórica.
            $sqlHistorial = "
    INSERT INTO direcciones_historial
    (
        direccion_id,
        usuario_id,
        codigo_postal,
        nombre_via,
        numero,
        piso,
        puerta,
        pais,
        tipo,
        was_deleted,
        created_at
    )
    VALUES
    (
        :direccion_id,
        :usuario_id,
        :codigo_postal,
        :nombre_via,
        :numero,
        :piso,
        :puerta,
        :pais,
        :tipo,
        NOW(),
        NOW()
    )
";
            // Prepara la consulta que registra la dirección en el historial.
            $stmtHistorial = $this->pdo->prepare($sqlHistorial);
            // Inserta los datos recuperados antes de eliminar la dirección.
            $stmtHistorial->execute([
                ':direccion_id' => $direccionId,
                ':usuario_id' => $usuarioId,
                ':codigo_postal' => $direccion['codigo_postal'],
                ':nombre_via' => $direccion['nombre_via'],
                ':numero' => $direccion['numero'],
                ':piso' => $direccion['piso'],
                ':puerta' => $direccion['puerta'],
                ':pais' => $direccion['pais'],
                ':tipo' => $direccion['tipo']
            ]);
            // Prepara la consulta que elimina la dirección original.
            // Comprueba también el ID del usuario para evitar
            // eliminar una dirección que pertenezca a otra persona.
            $sqlEliminar = "
            DELETE FROM direcciones
            WHERE id = :id
            AND usuario_id = :usuario_id
        ";
            // Prepara la consulta de eliminación.
            $stmtEliminar = $this->pdo->prepare($sqlEliminar);
            // Ejecuta la eliminación con los identificadores correspondientes.
            $stmtEliminar->execute([
                ':id' => $direccionId,
                ':usuario_id' => $usuarioId
            ]);
            // Confirma los cambios: historial guardado y dirección eliminada.
            $this->pdo->commit();
            // Indica que la operación se ha completado correctamente.
            return true;
        } catch (PDOException $e) {
            // Si se produce un error y la transacción sigue abierta,
            // deshace las operaciones realizadas.
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            // Propaga el error para que pueda gestionarse en el controlador.
            throw $e;
        }
    }
}