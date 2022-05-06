<?php
  require_once "./config.php";
  require_once TEMPLATE_HEADER;
?>

<?php
  if ( !empty($_POST) && isset($_POST["acao"]) && ($_POST["acao"] === "buscar") && !empty($_POST["busca"]) && isset($_POST["busca"]) ) {
    $pesquisa = true;
    $conn = open_db();
    $busca = mysqli_real_escape_string( $conn, $_POST["busca"] );
    $acao = mysqli_real_escape_string( $conn, $_POST["acao"] );

    $busca = str_replace( "\"", "'", $busca );

    $result = $conn->query("SELECT id_contribuicao, contribuicao, significados, id_autor FROM contribuicao WHERE situacao LIKE 'Aprovada' AND ( contribuicao LIKE '%" . $busca . "%' OR significados LIKE '%" . $busca . "%') ORDER BY contribuicao");

    if ($result->num_rows === 0) $sem_contribuicoes = true;
    else $sem_contribuicoes = false;

  } else $pesquisa = false;
?>

<form action="" method="post" class="row g-0 gap-3 mx-auto col">
  <input type="text" class="form-control col" placeholder="Pesquise por uma palavra" name="busca" value="<?php if (isset($_POST["busca"])) echo $_POST["busca"] ?>">
  <button class="btn btn-primary col-auto" type="submit" name="acao" value="buscar">Buscar</button>
</form>

<?php if ($pesquisa) : ?>
  <?php if ($sem_contribuicoes) : ?>
    <div class='alert alert-secondary text-center m-0'>Não foram encontrados resultados para "<?php echo $busca ?>"</div>
  <?php else : ?>
    <h1 class="h2 text-center m-0">Resultados encontrados para "<?php echo $busca ?>"</h1>

    <div class="row g-0 gap-3">
      <?php while ($contribuicao = $result->fetch_object()) : ?>
        <?php
          $result2 = $conn->query("SELECT nome_usuario FROM usuario WHERE id_usuario = $contribuicao->id_autor");
          $usuario = $result2->fetch_object();
        ?>
        <div>
          <a href="<?php echo BASE_URL ?>pages/contribuicoes/detalhes_contribuicao.php?id_contribuicao=<?php echo $contribuicao->id_contribuicao ?>" class="nav-link p-0 m-0">
            <span class="fw-bold"><?php echo $contribuicao->contribuicao ?></span><br/>
          </a>
          <span><?php echo $contribuicao->significados ?></span><br/>
          <span>Por: <a href="<?php echo BASE_URL ?>pages/user/perfil.php?id_usuario=<?php echo $contribuicao->id_autor ?>" class="nav-link d-inline p-0"><?php echo $usuario->nome_usuario ?></a></span>
        </div>
      <?php endwhile ?>
    </div>
  <?php endif ?>
<?php endif ?>

<?php require_once TEMPLATE_FOOTER ?>
