<?php

$hoja=new hojeador;
$hoja->iniciar_variables();

if(isset($_GET['comenzar'])){
  $hoja->abrir_canal()
  && $hoja->mandar_autenticacion()
  && $hoja->obtener_respuesta()
  && $hoja->cerrar_canal()
  ;;
}else{
  phpinfo();
}

class hojeador{
  var $user;
  var $pass;
  var $planilla;
  var $canal;

  function iniciar_variables(){
    $this->user='quieromisnotas';
    $this->pass='perolasquieroya';
    $this->planilla='QuieroMisNotas-CasoEjemplo';
  }

  function debugm($mensaje){
    print "<p><b><i><small>$mensaje</small></i></b></p>\n";
  }

  function abrir_canal(){
    $errno=-1;
    $errstr="";
    $this->debugm("conectando");
    $this->canal=fsockopen("www.google.com", 80, $errno, $errstr, 30);
    $this->debugm("resultado conexión ($errno), ($errstr)");
    if($errno<>0){
      return false;
    }
    if(!stream_set_blocking($this->canal,false)){
      $this->debugm("no pude poner la conexión en no bloqueante");
      return false;
    }
    return true;
  }

  function mandar_autenticacion(){
    $contenido=
      "Email=".urlencode($this->user.'@gmail.com').
        "&Passwd=".urlencode($this->pass).
        "&service=cl&source=".urlencode($this->planilla)."\n";
    $longitud=strlen($contenido);
    $enviar=
      "POST /accounts/ClientLogin HTTP/1.0\n".
      "Content-type: application/x-www-form-urlencoded\n".
      "Content-length: $longitud\n\n".
      $contenido."\n\n";
    //  "Connection: Close\r\n\r\n";
    $this->debugm("enviando autenticacion");
    fwrite($this->canal, $enviar);
    return true;
  }

  function obtener_respuesta(){
    $this->debugm("recibiendo");
    $i=1;
    while (!feof($this->canal) && ($i<200000)) {
        $i++;
        $s=fgets($this->canal, 1000);
        if($s){
          print "<p>".$i.":".$s."</p>\n";
        }
    }
    $this->debugm("fin de la respuesta");
    return true;
  }

  function cerrar_canal(){
    $this->debugm("cerrando");
    fclose($this->canal);
    return true;
  }

}
?>
