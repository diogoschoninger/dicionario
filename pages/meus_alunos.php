<?php
  require_once "../config.php";
  require_once TEMPLATE_HEADER
?>

<?php
  if ( empty($_SESSION) || ( $_SESSION["nivel"] !== "2" && $_SESSION["nivel"] !== "3" ) ) {
    header("Location: " . BASE_URL);
    exit();
  }
?>

<h1 class="fs-2 text-center">Meus alunos</h1>

<?php
  $conn = open_db();
  $result = $conn->query("SELECT id_usuario, nome_usuario, email FROM usuario WHERE id_responsavel = {$_SESSION["id_usuario"]} AND nivel = 1");
  if ($result->num_rows === 0) echo "<p class='fs-5 mt-3 text-center'>Você não é responsável por nenhum aluno.</p>";
  if ($result->num_rows !== 0) :
?>
  <table class="table container">
    <thead class="border-bottom-none">
      <th>Aluno</th>
      <th>Email</th>
      <th></th>
    </thead>
    <tbody>
      <?php while ($usuario = $result->fetch_object()) : ?>
        <tr>
          <td><?php echo $usuario->nome_usuario ?></td>
          <td><?php echo $usuario->email ?></td>
          <td><a class="btn btn-primary" href="<?php echo BASE_URL ?>pages/user/perfil.php?id_usuario=<?php echo $usuario->id_usuario ?>">Visitar perfil</a></td>
        </tr>
      <?php endwhile ?>
    </tbody>
  </table>
<?php endif ?>
<?php require_once TEMPLATE_FOOTER ?>
