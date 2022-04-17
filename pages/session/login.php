<?php
  require_once "../../config.php";
  require_once TEMPLATE_HEADER;
?>

<?php
  if ( !isset($_POST["cpf"]) || !isset($_POST["senha"]) ) {
    if ( !empty( $_SESSION ) ) {
      header("Location: " . BASE_URL);
      exit();
    }
  } else {
    $conn = open_db();

    $cpf = mysqli_real_escape_string( $conn, $_POST["cpf"] );
    $senha = mysqli_real_escape_string( $conn, $_POST["senha"] );

    $result = $conn->query("SELECT id_usuario, nome_usuario, foto, data_nasc, email, nivel, primeiro_acesso FROM usuario WHERE cpf LIKE '$cpf' AND senha LIKE md5('$senha')");
    
    close_db($conn);

    if ( $result->num_rows !== 1 ) {
      header("Location: ./login.php?e=1");
    } else {
      $usuario = $result->fetch_assoc();
      $_SESSION["id_usuario"] = $usuario["id_usuario"];
      $_SESSION["nome_usuario"] = $usuario["nome_usuario"];
      $_SESSION["foto"] = $usuario["foto"];
      $_SESSION["data_nasc"] = $usuario["data_nasc"];
      $_SESSION["email"] = $usuario["email"];
      $_SESSION["nivel"] = $usuario["nivel"];
      $_SESSION["primeiro_acesso"] = $usuario["primeiro_acesso"];

      if ( $_SESSION["primeiro_acesso"] == 1 ) {
        header("Location: " . BASE_URL . "pages/user/alterar_senha.php");
      } else header("Location: " . BASE_URL);
    }
  }
?>

<div class="row g-0 gap-3 mx-auto col-sm-7 col-md-5 col-lg-4 col-xl-3 col-xxl-3">
  <div class="row g-0">
    <span class="fs-2 text-center">Login</span>
  </div>

  <?php
    if ( isset( $_GET["e"] ) ) {
      $erro = $_GET["e"];
      if ( $erro == 1 ) {
        echo "<div class='alert alert-danger m-0'>Credenciais incorretas!</div>";
      }
    }
  ?>

  <form action="./login.php" method="post" class="row g-0 gap-3 p-3 rounded shadow">
    <div class="row g-0 gap-2">
      <label class="fs-5">CPF</label>
      <input class="form-control" type="number" name="cpf">
    </div>
    <div class="row g-0 gap-2">
      <label class="fs-5">Senha</label>
      <input class="form-control" type="password" name="senha">
    </div>
    <button type="submit" class="btn btn-primary fs-5">Entrar</button>
  </form>
</div>

<?php require_once TEMPLATE_FOOTER ?>
