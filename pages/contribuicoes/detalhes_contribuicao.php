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

<h1 class="fs-2 text-center">Detalhes de "<?php echo $contribuicao->contribuicao ?>"</h1>

<div class="container">  
  <div class="row mb-3">
    <h2 class="fs-4 mb-2">Silabação</h2>
    <span class="ms-3"><?php echo $contribuicao->silabacao ?></span>
  </div>
  
  <div class="row mb-3">
    <h2 class="fs-4 mb-2">Classe gramatical</h2>
    <span class="ms-3"><?php echo $contribuicao->classe_gramatical ?></span>
  </div>
  
  <div class="row mb-3">
    <h2 class="fs-4 mb-2">Significados</h2>
    <span class="ms-3"><?php echo $contribuicao->significados ?></span>
  </div>

  <div class="row mb-3">
    <h2 class="fs-4 mb-2">Exemplos de uso</h2>
    <div class="ms-3" style="max-width: 100vw">
      <?php
        $exemplos = explode(" ", $contribuicao->exemplos);
        
        for ($i = 0; $i < count($exemplos); $i++) :
      ?>
        <img src="<?php echo BASE_URL . $exemplos[$i] ?>" style="width: 200px; height: 200px; object-fit: contain; cursor: pointer" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $i ?>">
        <div class="modal fade" id="exampleModal<?php echo $i ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl">
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

  <div class="row mb-3">
    <h2 class="fs-4 mb-2">Formação da palavra</h2>
    <span class="ms-3"><?php echo $contribuicao->formacao ?></span>
  </div>
  
  <div class="row mb-3">
    <h2 class="fs-4 mb-2">Comentários</h2>
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
</div>

<?php require_once TEMPLATE_FOOTER ?>
