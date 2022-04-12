<?php
  require_once "../../config.php";
  require_once TEMPLATE_HEADER
?>

<?php
  if ( empty($_GET) || empty($_SESSION) || !isset($_GET["id_contribuicao"]) || empty($_GET["id_contribuicao"]) || $_SESSION["nivel"] != 1) {
    header("Location: " . BASE_URL);
    exit();
  }

  if (!empty($_POST) && !empty($_FILES)) {
    $conn = open_db();

    $id_contribuicao   = mysqli_real_escape_string( $conn, $_GET["id_contribuicao"] );
    $contribuicao      = mysqli_real_escape_string( $conn, $_POST["contribuicao"] );
    $silabacao         = mysqli_real_escape_string( $conn, $_POST["silabacao"] );
    $classe_gramatical = mysqli_real_escape_string( $conn, $_POST["classe_gramatical"] );
    $significados      = mysqli_real_escape_string( $conn, $_POST["significados"] );
    $formacao          = mysqli_real_escape_string( $conn, $_POST["formacao"] );
    $comentarios       = mysqli_real_escape_string( $conn, $_POST["comentarios"] );

    $contribuicao      = str_replace("\"", "'", $contribuicao);
    $silabacao         = str_replace("\"", "'", $silabacao);
    $classe_gramatical = str_replace("\"", "'", $classe_gramatical);
    $significados      = str_replace("\"", "'", $significados);
    $formacao          = str_replace("\"", "'", $formacao);
    $comentarios       = str_replace("\"", "'", $comentarios);

    $exemplos = $_FILES["exemplos"];

    $query = "UPDATE contribuicao SET contribuicao = '$contribuicao', silabacao = '$silabacao', classe_gramatical = '$classe_gramatical', significados = '$significados', formacao = '$formacao', comentarios = '$comentarios', situacao = 'Pendente', exemplos = '";

    for ( $i = 0; $i < count($exemplos["name"]); $i++ ) {
      if (!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $exemplos["type"][$i])) {
        echo "<script>document.write('<div class=\'alert alert-danger\'>Os arquivos selecionados não são imagens!</div>')</script>";
        exit();
      }
      
      preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $exemplos["name"][$i], $extensao);

      $nome_imagem = md5(uniqid()) . "." . $extensao[1];
      
      $caminho_absoluto_imagem = str_replace("\\", "/", ABS_PATH) . "public/img/contribuicoes/" . $nome_imagem;
      
      move_uploaded_file($exemplos["tmp_name"][$i], $caminho_absoluto_imagem);

      $caminho_base_imagem = "public/img/contribuicoes/" . $nome_imagem;

      if ( $i < count($exemplos["name"]) - 1 ) $query .= $caminho_base_imagem . " ";
      else $query .= $caminho_base_imagem;
    }

    $query .= "' WHERE id_contribuicao = " . $id_contribuicao . " AND id_autor = " . $_SESSION["id_usuario"] . ";";
    
    $result = $conn->query($query);
    
    if (!$result) {
      echo $conn->errno . "<br>";
      echo $conn->error;
    } else {
      echo "<script>document.write('<div class=\'alert alert-success\'>Contribuição enviada para correção!</div>')</script>";
      unset($_POST);
    }

    close_db($conn);
  }

  $conn = open_db();
  $id_contribuicao = mysqli_real_escape_string( $conn, $_GET["id_contribuicao"] );
  $result = $conn->query("SELECT * FROM contribuicao WHERE id_contribuicao = $id_contribuicao AND id_autor = " . $_SESSION["id_usuario"]);
  close_db($conn);

  if (!$result || $result->num_rows !== 1) {
    header("Location: " . BASE_URL);
    exit();
  }
  
  $contribuicao = $result->fetch_object();
?>

<h1 class="fs-2 text-center">Corrigir gíria/palavrão</h1>

<form action="" method="post" class="container" enctype="multipart/form-data">
  <div class="row mb-3">
    <label class="col-form-label col-2">Gíria/palavrão</label>
    <div class="col-10">
      <input class="form-control" name="contribuicao" required value="<?php if (isset($_POST)) echo $contribuicao->contribuicao ?>" >
    </div>
  </div>
  
  <div class="row mb-3">
    <label class="col-form-label col-2">Silabação</label>
    <div class="col-10">
      <input class="form-control" name="silabacao" required value="<?php if (isset($_POST)) echo $contribuicao->silabacao ?>" >
    </div>
  </div>
  
  <div class="row mb-3">
    <label class="col-form-label col-2">Classe gramatical</label>
    <div class="col-10">
      <input class="form-control" name="classe_gramatical" required value="<?php if (isset($_POST)) echo $contribuicao->classe_gramatical ?>" >
    </div>
  </div>
  
  <div class="row mb-3">
    <label class="col-form-label col-2">Significados</label>
    <div class="col-10">
      <input class="form-control" name="significados" required value="<?php if (isset($_POST)) echo $contribuicao->significados ?>" >
    </div>
  </div>

  <div class="row mb-3">
    <label class="col-form-label col-2">Exemplos de uso</label>
    <div class="col-10">
      <input class="form-control" type="file" multiple name="exemplos[]" required>
    </div>
  </div>

  <div class="row mb-3">
    <label class="col-form-label col-2">Formação da palavra</label>
    <div class="col-10">
      <input class="form-control" name="formacao" required value="<?php if (isset($_POST)) echo $contribuicao->formacao ?>" >
    </div>
  </div>
  
  <div class="row mb-3">
    <label class="col-form-label col-2">Comentários</label>
    <div class="col-10">
      <textarea class="form-control" name="comentarios"><?php if (isset($_POST)) echo $contribuicao->comentarios ?></textarea>
    </div>
  </div>
  
  <div class="row mb-3">
    <label class="col-form-label col-2">Avaliação do professor</label>
    <div class="col-10">
      <textarea class="form-control" disabled readonly><?php if (isset($_POST)) echo $contribuicao->comentarios_avaliador ?></textarea>
    </div>
  </div>

  <div class="row justify-content-center">
    <button type="submit" class="btn btn-primary w-auto">Enviar para correção</button>
  </div>
</form>

<?php require_once TEMPLATE_FOOTER ?>
