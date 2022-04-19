<?php
  require_once "../config.php";
  require_once TEMPLATE_HEADER
?>

<?php
  if (empty($_SESSION) || ($_SESSION["nivel"] !== "2" && $_SESSION["nivel"] !== "3")) {
    header("Location: " . BASE_URL);
    exit();
  }
?>

<h1 class="fs-2 text-center m-0">Meus alunos</h1>

<?php
  $conn = open_db();
  $result = $conn->query("SELECT id_usuario, foto, nome_usuario, email FROM usuario WHERE id_responsavel = {$_SESSION["id_usuario"]} AND nivel = 1");
  if ($result->num_rows === 0) echo "<div class='alert alert-secondary m-0'>Você não é responsável por nenhum aluno.</div>";
  if ($result->num_rows !== 0) :
?>
  <div class="row g-0 gap-3 mx-auto col-md-9 col-lg-7 col-xl-6 col-xxl-5">
    <?php while ($usuario = $result->fetch_object()) : ?>
      <div class="row g-0 gap-3 justify-content-center">
        <img src="<?php echo BASE_URL; echo $usuario->foto ?? IMG_DEFAULT ?>" class="row g-0 rounded-circle" style="object-fit: cover; width: 5rem; height: 5rem;">
        <div class="row g-0 col d-flex align-content-center">
          <a class="nav-link p-0 row g-0" href="<?php echo BASE_URL ?>pages/user/perfil.php?id_usuario=<?php echo $usuario->id_usuario ?>"><?php echo $usuario->nome_usuario ?></a>
          <span class="row g-0"><?php echo $usuario->email ?></span>
        </div>
      </div>
      <hr class="m-0"/>
    <?php endwhile ?>
  </div>
<?php endif ?>
<?php require_once TEMPLATE_FOOTER ?>
