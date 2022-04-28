<?php
  require_once "../../config.php";
  require_once TEMPLATE_HEADER;
?>

<?php
  if (empty($_SESSION) || $_SESSION["nivel"] !== "1") {
    header("Location: " . BASE_URL);
    exit();
  }
?>

<?php
  if (!empty($_POST) && !empty($_FILES)) {
    $conn = open_db();

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

    $erro = false;
    for ($i = 0; $i < count($exemplos["name"]); $i++) {
      if (!$erro) {
        if (!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $exemplos["type"][$i])) {
          echo "<div class='alert alert-danger alert-dismissible fade show m-0' role='alert'>Os arquivos enviados não são imagens!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
          $erro = true;
          unset($_FILES);
        }
      } else break;
    }

    if (!$erro) {
      $query = "INSERT INTO contribuicao (contribuicao, silabacao, classe_gramatical, significados, formacao, comentarios, situacao, exemplos, id_autor)
        VALUES ('$contribuicao', '$silabacao', '$classe_gramatical', '$significados', '$formacao', '$comentarios', 'Pendente', '";

      for ($i = 0; $i < count($exemplos["name"]); $i++) {
        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $exemplos["name"][$i], $extensao);

        $nome_imagem = md5(uniqid()) . "." . $extensao[1];
        
        $caminho_absoluto_imagem = str_replace("\\", "/", ABS_PATH) . "src/img/contribuicoes/" . $nome_imagem;
        
        move_uploaded_file($exemplos["tmp_name"][$i], $caminho_absoluto_imagem);

        $caminho_base_imagem = "src/img/contribuicoes/" . $nome_imagem;

        if ($i < count($exemplos["name"]) - 1) $query .= $caminho_base_imagem . " ";
        else $query .= $caminho_base_imagem;
      }

      $query .= "', ". $_SESSION["id_usuario"] . ");";
      
      $results = $conn->query($query);
      
      if (!$results) {
        echo $conn->errno . "<br>";
        echo $conn->error;
      } else {
        echo "<div class='alert alert-success alert-dismissible fade show m-0' role='alert'>Contribuição enviada para correção!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        unset($_POST);
      }

      close_db($conn);
    }
  }
?>

<h1 class="fs-2 text-center m-0">Cadastrar nova gíria/palavrão</h1>

<form class="row g-0 gap-3" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post" enctype="multipart/form-data">
  <div class="row g-0 gap-3">
    <div class="row g-0 col-sm">
      <label>Gíria/palavrão</label>
      <input class="form-control" name="contribuicao" required <?php if (isset($_POST["contribuicao"])) echo "value=\"" . str_replace("\"", "'", $_POST["contribuicao"]) . "\"" ?> >
    </div>
    
    <div class="row g-0 col-sm">
      <label>Silabação</label>
      <input class="form-control" name="silabacao" required <?php if (isset($_POST["silabacao"])) echo "value=\"" . str_replace("\"", "'", $_POST["silabacao"]) . "\"" ?> >
    </div>
  </div>
  
  <div class="row g-0 gap-3">
    <div class="row g-0 col-md">
      <label>Classe gramatical</label>
      <input class="form-control" name="classe_gramatical" required <?php if (isset($_POST["classe_gramatical"])) echo "value=\"" . str_replace("\"", "'", $_POST["classe_gramatical"]) . "\"" ?> >
    </div>
    
    <div class="row g-0 col-md">
      <label>Significados</label>
      <input class="form-control" name="significados" required <?php if (isset($_POST["significados"])) echo "value=\"" . str_replace("\"", "'", $_POST["significados"]) . "\"" ?> >
    </div>
  </div>

  <div class="row g-0">
    <label>Exemplos de uso</label>
    <input class="form-control" type="file" multiple name="exemplos[]" required>
  </div>

  <div class="row g-0">
    <label>Formação da palavra</label>
    <input class="form-control" name="formacao" required <?php if (isset($_POST["formacao"])) echo "value=\"" . str_replace("\"", "'", $_POST["formacao"]) . "\"" ?> >
  </div>
  
  <div class="row g-0">
    <label>Comentários</label>
    <textarea class="form-control" name="comentarios"><?php if (isset($_POST["comentarios"])) echo str_replace("\"", "'", $_POST["comentarios"]) ?></textarea>
  </div>

  <button class="btn btn-primary w-auto mx-auto" type="submit">Enviar para correção</button>
</form>

<?php require_once TEMPLATE_FOOTER ?>
