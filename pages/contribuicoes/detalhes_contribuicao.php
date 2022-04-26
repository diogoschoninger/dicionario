<?php
  require_once "../../config.php";
  require_once TEMPLATE_HEADER
?>

<?php
  if (empty($_GET) || !isset($_GET["id_contribuicao"])) {
    header("Location: " . BASE_URL);
    exit();
  }
?>

<?php
  $conn = open_db();

  $id_contribuicao = mysqli_real_escape_string( $conn, $_GET["id_contribuicao"] );

  $result = $conn->query("SELECT contribuicao, silabacao, classe_gramatical, significados, exemplos, formacao, comentarios, id_autor FROM contribuicao WHERE id_contribuicao = $id_contribuicao");

  if (!$result) {
    echo $conn->errno . "<br>";
    echo $conn->error;
  } else if ($result->num_rows !== 1){
    header("Location: " . BASE_URL);
    exit();
  } else {
    $contribuicao = $result->fetch_object();
  }

  close_db($conn);
?>


<h1 class="fs-2 text-center m-0">Detalhes de "<?php echo $contribuicao->contribuicao ?>"</h1>
 
<div class="row g-0 gap-3 col-sm">
  <h2 class="fs-4 m-0">Silabação</h2>
  <input class="p-2" type="text" disabled value="<?php echo $contribuicao->silabacao ?>"/>
</div>

<div class="row g-0 gap-3 col-sm">
  <h2 class="fs-4 m-0">Classe gramatical</h2>
  <input class="p-2" type="text" disabled value="<?php echo $contribuicao->classe_gramatical ?>"/>
</div>

<div class="row g-0 gap-3">
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

<div class="row g-0 gap-3">
  <h2 class="fs-4 m-0">Formação da palavra</h2>
  <input class="p-2" type="text" disabled value="<?php echo $contribuicao->formacao ?>"/>
</div>

<div class="row g-0 gap-3">
  <h2 class="fs-4 m-0">Comentários do autor</h2>
  <input class="p-2" type="text" disabled value="<?php echo $contribuicao->comentarios ?>"/>
</div>

<?php
  $conn = open_db();
  $results = $conn->query("SELECT nome_usuario FROM usuario WHERE id_usuario = $contribuicao->id_autor");
  $autor = $results->fetch_object();
  close_db($conn);
?>

<div class="row g-0">
  <span>Por: <a class="nav-link d-inline px-0" href="<?php echo BASE_URL ?>pages/user/perfil.php?id_usuario=<?php echo $contribuicao->id_autor ?>"><?php echo $autor->nome_usuario ?></a></span>
</div>

<?php require_once TEMPLATE_FOOTER ?>
