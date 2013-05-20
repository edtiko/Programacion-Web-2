<?php

Class DataBase {

  /**Datos de la conexion en el server de ctis.regencysa.net*/
  private $user = "root";
  private $pass = "root";
  private $host = "localhost";
  private $base_datos = "todosuniajc";
  private $error;
  private $numerror;
  private $conexion;
  private $resource;
  private $sql;
  public  $queries;
  private static $_singleton;

  public static function getInstance() {
    if (is_null(self::$_singleton)) {
      self::$_singleton = new DataBase();
    }
    return self::$_singleton;
  }

  private function __construct() {
    if (!$this -> conexion = pg_connect('host='.$this->host.' port=5432 dbname='.$this->base_datos.' user='.$this->user.' password='.$this->pass)) {
      $this -> error = "Error conectando a la base de datos.";
    }
    $this->queries = 0;
    $this->resource = null;
    $this->numerror = 0;
  }

  public function execute() { 
    if (!($this->resource = pg_query( $this->conexion, $this->sql))) {
      $this->numerror++;
      return null;
    }
    $this->queries++;
    return $this->resource;
  }

  public function alter() {
    //        echo 'en alter';
    if (!($this -> resource = pg_query($this -> sql, $this -> conexion))) {
      $this -> error = pg_errno() . pg_error();
      $this -> numerror++;
      return false;
    }
    return true;
  }

  public function loadObjectList() {
    if (!($cur = $this -> execute())) {
      return null;
    }
    $array = array();
    while ($row = @pg_fetch_object($cur)) {
      $array[] = $row;
    }
    return $array;
  }

  public function loadArray() {
    if (!($cur = $this->execute())) {
      return null;
    }
    $array = array();
    while ($row = pg_fetch_array($cur,null,PGSQL_ASSOC)) {
      $array[] = $row;
    }
    return $array;
  }

  public function setQuery($sql) {
    if (empty($sql)) {
      return false;
    }
    $this -> sql = $sql;
    return true;
  }

  public function freeResults() {
    @pg_free_result($this -> resource);
    return true;
  }

  public function loadObject() {
    if ($cur = $this -> execute()) {                    
      if ($object = pg_fetch_object($cur)) {
        @pg_free_result($cur);
        return $object;
      } else {
        return null;
      }
    } else {
      return false;
    }
  }

  public function getFieldNames() {
    return $names = pg_fetch_field($this -> resource);
  }

  public function getError() {
    return $this -> error;
  }

  public function get_last_id() {
    $id = pg_insert_id($this -> conexion);
    return $id;
  }

  //    public function get_affected() {
  //        $aff=pg_affected_rows($this->conexion);
  //        return $aff;
  //    }

  public function empezar_transaccion() {

    pg_query("BEGIN", $this -> conexion);
  }

  public function terminar_transaccion() {

    if ($this -> numerror != 0) {
      pg_query("ROLLBACK", $this -> conexion);
      return false;
      //            echo "Transaccion Cancelada numero de errores: $this->numerror, ultimo error: $this->error";
    } else {
      pg_query("COMMIT", $this -> conexion);
      return true;
      //            echo "todo OKKKKK";
    }
  }

  function __destruct() {
    @pg_free_result($this -> resource);
    @pg_close($this -> conexion);
  }

}
?>