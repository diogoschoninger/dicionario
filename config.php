<?php
  session_start();

  define( "DB_HOST", "localhost" );
  define( "DB_USER", "root" );
  define( "DB_PASS", "" );
  define( "DB_NAME", "dicionario" );

  define( "BASE_URL", "/dicionario/" );
  define( "ABS_PATH" , dirname(__FILE__) . "/" );
  define( "TEMPLATE_HEADER", ABS_PATH . "src/templates/header.php" );
  define( "TEMPLATE_FOOTER", ABS_PATH . "src/templates/footer.php" );
  define( "IMG_DEFAULT", BASE_URL . "src/img/default.jpg" );
  define( "VERSION", "1.2.1" );

  $url_atual = $_SERVER["REQUEST_URI"];

  date_default_timezone_set("America/Sao_Paulo");

  function open_db() {
    $conn = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME ) or die ( "Erro ao conectar ao banco de dados." );
    mysqli_set_charset($conn, "utf8");
    return $conn;
  }
  function close_db( $conn ) {
    mysqli_close( $conn );
  }
?>
