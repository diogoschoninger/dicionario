<?php
  require_once "../../config.php";
  require_once TEMPLATE_HEADER;
?>

<?php
  if ( empty($_SESSION) ) {
    header("Location: " . BASE_URL);
    exit();
  }
?>

<?php
  if ( !empty( $_POST ) ) {
    $conn = open_db();

    $senha_atual = mysqli_real_escape_string( $conn, $_POST["senha_atual"] );
    $nova_senha1 = mysqli_real_escape_string( $conn, $_POST["nova_senha1"] );
    $nova_senha2 = mysqli_real_escape_string( $conn, $_POST["nova_senha2"] );
    $id_usuario = $_SESSION["id_usuario"];

    if ( $nova_senha1 !== $nova_senha2 ) {
      echo "<script>document.write('<div class=\'alert alert-danger\'>As senhas não coincidem!</div>')</script>";
      exit();
    } else {
      if (strlen($nova_senha1) < 8) {
        echo "<script>document.write('<div class=\'alert alert-danger\'>A nova senha deve ter, no mínimo, 8 caracteres.</div>')</script>";
      } else {
        $result = $conn->query("SELECT id_usuario, primeiro_acesso FROM usuario WHERE id_usuario = $id_usuario AND senha LIKE md5('$senha_atual')");
        
        if ( $result->num_rows !== 1 ) {
          echo "<script>document.write('<div class=\'alert alert-danger\'>Senha atual incorreta!</div>')</script>";
        } else {
          $result = $conn->query("UPDATE usuario SET senha = md5('$nova_senha1'), primeiro_acesso = false WHERE id_usuario = $id_usuario");
          
          if ( $result ) {
            if ( $_SESSION["primeiro_acesso"] == 1 ) {
              echo "<script>document.write('<div class=\'alert alert-success\'>Senha criada com sucesso!</div>')</script>";
            } else echo "<script>document.write('<div class=\'alert alert-success\'>Senha atualizada com sucesso!</div>')</script>";
          } else {
            echo $conn->errno . "<br>";
            echo $conn->error;
          }
        }
      }
      close_db($conn);
    }
  }
?>

<div class="h-100 d-flex flex-column justify-content-center align-items-center">
  <?php if ( $_SESSION["primeiro_acesso"] == 1 ) : ?>
    <h1 class="fs-2 text-center">Crie sua senha</h1>
  <?php else : ?>
    <h1 class="fs-2 text-center mb-5">Atualização de senha</h1>
  <?php endif ?>

  <form action="" method="post" class="col-3 rounded p-3 mb-3 shadow-sm">
    <div class="mb-3">
      <label class="mb-1 fs-5">Senha atual</label>
      <input class="form-control" type="password" name="senha_atual" required>
    </div>
    <div class="mb-3">
      <label class="mb-1 fs-5">Nova senha</label>
      <input class="form-control" type="password" name="nova_senha1" required>
    </div>
    <div class="mb-3">
      <label class="mb-1 fs-5">Confirme a nova senha</label>
      <input class="form-control" type="password" name="nova_senha2" required>
    </div>

    <button type="submit" class="btn w-100 btn-primary fs-5">
      <?php
        if ( $_SESSION["primeiro_acesso"] == 1 ) echo "Criar Senha";
        else echo "Atualizar senha"
      ?>
    </button>
  </form>
</div>

<?php require_once TEMPLATE_FOOTER ?>
