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

<?php if (!$pesquisa) : ?>
  <script>
    var div = document.getElementById('content')
    div.classList.remove('p-3')
  </script>
<?php endif ?>

<div class="container">
  <div class="row d-flex align-items-center" style="height: 100vh">
    <form action="" method="post" class="form-inline d-flex justify-content-center">
      <input type="text" class="form-control me-2 w-25" placeholder="Pesquise por uma palavra" name="busca" value="<?php if (isset($_POST["busca"])) echo $_POST["busca"] ?>">
      <button class="btn btn-primary" type="submit" name="acao" value="buscar">Buscar</button>
    </form>
  </div>

  <?php if ($pesquisa) : ?>
    <script>
      var div = document.querySelector('div#content div.container div.row')
      div.classList.remove('align-items-center')
      div.style = '';
    </script>

    <div class="mt-3">
      <?php if (!$sem_contribuicoes) : ?>
        <h1 class="fs-2 text-center">Resultados encontrados para "<?php echo $busca ?>"</h1>
      <?php endif ?>

      <?php if ($sem_contribuicoes) : ?>
        <p class='fs-5 mt-3 text-center'>Não foram encontrados resultados para "<?php echo $busca ?>"</p>
      <?php else : ?>
        <table class="table table-sm">
          <tbody>
            <?php while ($contribuicao = $result->fetch_object()) : ?>
              <?php
                $result2 = $conn->query("SELECT nome_usuario FROM usuario WHERE id_usuario = $contribuicao->id_autor");
                $usuario = $result2->fetch_object();
              ?>
              <tr>
                <td>
                  <a href="<?php echo BASE_URL ?>pages/contribuicoes/detalhes_contribuicao.php?id_contribuicao=<?php echo $contribuicao->id_contribuicao ?>" class="nav-link px-0">
                    <span class="fw-bold"><?php echo $contribuicao->contribuicao ?></span><br/>
                  </a>
                  <span><?php echo $contribuicao->significados ?></span><br/>
                  <span>Por: <a href="<?php echo BASE_URL ?>pages/user/perfil.php?id_usuario=<?php echo $contribuicao->id_autor ?>" class="nav-link d-inline px-0"><?php echo $usuario->nome_usuario ?></a></span>
                </td>
              </tr>
            <?php endwhile ?>
          </tbody>
        </table>
      <?php endif ?>
    </div>
  <?php endif ?>
</div>

<?php require_once TEMPLATE_FOOTER ?>
