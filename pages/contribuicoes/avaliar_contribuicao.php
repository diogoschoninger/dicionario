<?php
  require_once "../../config.php";
  require_once TEMPLATE_HEADER
?>

<?php
  if (empty($_SESSION) || ($_SESSION["nivel"] !== "2" && $_SESSION["nivel"] !== "3") || !isset($_GET["id_contribuicao"]) ) {
    header("Location: " . BASE_URL);
    exit();
  }
  $conn = open_db();
  $id_contribuicao = mysqli_real_escape_string($conn, $_GET["id_contribuicao"]);
  close_db($conn);
?>

<?php
  if (isset($_POST["acao"])) {
    $conn = open_db();
    $comentarios_avaliador = mysqli_real_escape_string($conn, $_POST["comentarios_avaliador"]);
    $acao = mysqli_real_escape_string($conn, $_POST["acao"]);

    $comentarios_avaliador = str_replace("\"", "'", $comentarios_avaliador);

    if ($acao == "aprovar") $query = "UPDATE contribuicao SET situacao = 'Aprovada', comentarios_avaliador = '$comentarios_avaliador' WHERE id_contribuicao = $id_contribuicao";
    else if ($acao == "correcao") $query = "UPDATE contribuicao SET situacao = 'Em correcao', comentarios_avaliador = '$comentarios_avaliador' WHERE id_contribuicao = $id_contribuicao";

    $results = $conn->query($query);

    if ($results) {
      echo "<div class='alert alert-success alert-dismissible fade show m-0' role='alert'>Contribuição avaliada com sucesso!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } else {
      echo "<div class='alert alert-danger alert-dismissible fade show m-0' role='alert'>" . $conn->error . "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }

    close_db($conn);
  }
?>

<?php
  $conn = open_db();

  $results = $conn->query("SELECT * FROM contribuicao WHERE id_contribuicao = $id_contribuicao");

  if (!$results) {
    echo "<div class='alert alert-danger alert-dismissible fade show m-0' role='alert'>" . $conn->error . "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
  } else if ($results->num_rows !== 1) {
    header("Location: " . BASE_URL);
    exit();
  } else {
    $contribuicao = $results->fetch_object();
  }

  close_db($conn);
?>

<?php
  $conn = open_db();
  $results = $conn->query("SELECT id_responsavel FROM usuario WHERE id_usuario = {$contribuicao->id_autor}");
  $usuario = $results->fetch_object();

  if ($usuario->id_responsavel !== $_SESSION["id_usuario"]) {
    header("Location: " . BASE_URL);
    exit();
  }
?>

<h1 class="fs-2 text-center m-0">Detalhes de "<?php echo $contribuicao->contribuicao ?>"</h1>
 
<div class="row g-0 gap-3 col">
  <h2 class="fs-4 m-0">Silabação</h2>
  <input class="p-2" type="text" disabled value="<?php echo $contribuicao->silabacao ?>"/>
</div>

<div class="row g-0 gap-3 col">
  <h2 class="fs-4 m-0">Classe gramatical</h2>
  <input class="p-2" type="text" disabled value="<?php echo $contribuicao->classe_gramatical ?>"/>
</div>

<div class="row g-0 gap-3 col-12">
  <h2 class="fs-4 m-0">Significados</h2>
  <div class="p-2 rounded" style="border: 1px solid rgba(118, 118, 118, 0.3); background-color: rgba(220, 220, 220, 0.3)" type="text" disabled>
    <?php echo $contribuicao->significados ?>
  </div>
</div>

<div class="row g-0 gap-3">
  <h2 class="fs-4 m-0">Exemplos de uso</h2>
  <div class="row g-0 gap-3 justify-content-center">
    <?php
      $exemplos = explode(" ", $contribuicao->exemplos);
      
      for ($i = 0; $i < count($exemplos); $i++) :
    ?>
      <img class="rounded" src="<?php echo BASE_URL . $exemplos[$i] ?>"  style="width: 200px; height: 200px; object-fit: cover; cursor: pointer" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $i ?>">
      <div class="modal fade" id="exampleModal<?php echo $i ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Exemplo</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <img src="<?php echo BASE_URL . $exemplos[$i] ?>" class="w-100">
            </div>
          </div>
        </div>
      </div>
    <?php endfor ?>
  </div>
</div>

<div class="row g-0 gap-3 col">
  <h2 class="fs-4 m-0">Formação da palavra</h2>
  <input class="p-2" type="text" disabled value="<?php echo $contribuicao->formacao ?>"/>
</div>

<div class="row mb-3">
  <h2 class="fs-4">Comentários</h2>
  <span class="ms-3"><?php echo $contribuicao->comentarios ?></span>
</div>

<?php
  $conn = open_db();

  $result = $conn->query( "SELECT nome_usuario FROM usuario WHERE id_usuario = $contribuicao->id_autor" );
  
  $autor = $result->fetch_object();

  close_db($conn);
?>

<div class="row">
  <span>Por: <a href="<?php echo BASE_URL ?>pages/user/perfil.php?id_usuario=<?php echo $contribuicao->id_autor ?>" class="nav-link d-inline px-0"><?php echo $autor->nome_usuario ?></a></span>
</div>

<hr>

<form action="" method="post">
  <div class="row mb-3">
    <label class="col-form-label">Deixar um comentário</label>
    <textarea class="form-control" name="comentarios_avaliador"><?php echo $contribuicao->comentarios_avaliador ?></textarea>
  </div>
  <div class="d-flex justify-content-center">
    <?php if ($contribuicao->situacao !== "Aprovada") : ?>
      <button class="btn btn-success w-auto me-2" type="submit" name="acao" value="aprovar">Aprovar</button>
    <?php endif ?>
    <button class="btn btn-danger w-auto" type="submit" name="acao" value="correcao">Solicitar correção</button>
  </div>
</form>

<?php require_once TEMPLATE_FOOTER ?>
