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
        echo "<script>document.write('<div class=\'alert alert-danger m-0\'>Os arquivos selecionados não são imagens!</div>')</script>";
        exit();
      }
      
      preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $exemplos["name"][$i], $extensao);

      $nome_imagem = md5(uniqid()) . "." . $extensao[1];
      
      $caminho_absoluto_imagem = str_replace("\\", "/", ABS_PATH) . "src/img/contribuicoes/" . $nome_imagem;
      
      move_uploaded_file($exemplos["tmp_name"][$i], $caminho_absoluto_imagem);

      $caminho_base_imagem = "src/img/contribuicoes/" . $nome_imagem;

      if ( $i < count($exemplos["name"]) - 1 ) $query .= $caminho_base_imagem . " ";
      else $query .= $caminho_base_imagem;
    }

    $query .= "' WHERE id_contribuicao = " . $id_contribuicao . " AND id_autor = " . $_SESSION["id_usuario"] . ";";

    $result = $conn->query("SELECT exemplos FROM contribuicao WHERE id_contribuicao = " . $id_contribuicao . " AND id_autor = " . $_SESSION["id_usuario"]);
    $old_exemplos = explode(" ", $result->fetch_object()->exemplos);
    for ($i = 0; $i < count($old_exemplos); $i++) {
      unlink(str_replace("\\", "/", ABS_PATH) . $old_exemplos[$i]);
    }
    
    $result = $conn->query($query);
    
    if (!$result) {
      echo $conn->errno . "<br>";
      echo $conn->error;
    } else {
      echo "<script>document.write('<div class=\'alert alert-success m-0\'>Contribuição enviada para correção!</div>')</script>";
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

<h1 class="fs-2 text-center m-0">Corrigir gíria/palavrão</h1>

<form class="row g-0 gap-3" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post" enctype="multipart/form-data">
  <div class="row g-0 gap-3">
    <div class="row g-0 col-sm">
      <label>Gíria/palavrão</label>
      <input class="form-control" name="contribuicao" required value="<?php if (isset($_POST)) echo $contribuicao->contribuicao ?>" />
    </div>
    
    <div class="row g-0 col-sm">
      <label>Silabação</label>
      <input class="form-control" name="silabacao" required value="<?php if (isset($_POST)) echo $contribuicao->silabacao ?>" />
    </div>
  </div>

  <div class="row g-0 gap-3">
    <div class="row g-0 col-md">
      <label>Classe gramatical</label>
      <input class="form-control" name="classe_gramatical" required value="<?php if (isset($_POST)) echo $contribuicao->classe_gramatical ?>" />
    </div>
    
    <div class="row g-0 col-md">
      <label>Significados</label>
      <input class="form-control" name="significados" required value="<?php if (isset($_POST)) echo $contribuicao->significados ?>" />
    </div>
  </div>

  <div class="row g-0">
    <label>Exemplos de uso</label>
    <input class="form-control" type="file" multiple name="exemplos[]" required />
  </div>

  <div class="row g-0">
    <label>Formação da palavra</label>
    <input class="form-control" name="formacao" required value="<?php if (isset($_POST)) echo $contribuicao->formacao ?>" />
  </div>
  
  <div class="row g-0">
    <label>Comentários</label>
    <textarea class="form-control" name="comentarios"><?php if (isset($_POST)) echo $contribuicao->comentarios ?></textarea>
  </div>
  
  <div class="row g-0">
    <label>Avaliação do professor</label>
    <div class="rounded" style="background-color: rgb(233, 236, 239); padding: 6px 12px; border: 1px solid rgb(206, 212, 218); min-height: 3rem;">
      <?php if (isset($_POST)) echo $contribuicao->comentarios_avaliador ?>
    </div>
  </div>

  <button class="btn btn-primary w-auto mx-auto" type="submit">Enviar para correção</button>
  </div>
</form>

<?php require_once TEMPLATE_FOOTER ?>
