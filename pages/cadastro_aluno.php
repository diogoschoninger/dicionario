<?php
  require_once "../config.php";
  require_once TEMPLATE_HEADER;
?>

<?php
  if ( empty($_SESSION) || ( $_SESSION["nivel"] !== "2" && $_SESSION["nivel"] !== "3" ) ) {
    header("Location: " . BASE_URL);
    exit();
  }
?>

<?php
  if ( !empty($_POST) ) {
    $conn = open_db();

    $nome_usuario = mysqli_real_escape_string( $conn, $_POST["nome_usuario"] );
    $cpf          = mysqli_real_escape_string( $conn, $_POST["cpf"] );
    $email        = mysqli_real_escape_string( $conn, $_POST["email"] );
    $data_nasc        = mysqli_real_escape_string( $conn, $_POST["data_nasc"] );
    $senha        = mysqli_real_escape_string( $conn, $_POST["senha"] );

    $result = $conn->query("INSERT INTO usuario (cpf, email, data_nasc, nome_usuario, senha, nivel, id_responsavel) VALUES ('$cpf', '$email', '$data_nasc', '$nome_usuario', md5('$senha'), 1, " . $_SESSION["id_usuario"] . ")");

    if ( $conn->errno === 1062 ) {
      if ( $conn->error === "Duplicate entry '{$email}' for key 'email'") {
        echo "<script>document.write('<div class=\'alert alert-danger\'>Já existe um aluno cadastrado com este Email</div>')</script>";
      } else if ( $conn->error === "Duplicate entry '{$cpf}' for key 'cpf'") {
        echo "<script>document.write('<div class=\'alert alert-danger\'>Já existe um aluno cadastrado com este CPF</div>')</script>";
      }
    } else {
      echo "<script>document.write('<div class=\'alert alert-success\'>Aluno cadastrado com sucesso!</div>')</script>";
    }
    
    close_db($conn);
  }
?>

<h1 class="fs-2 text-center">Cadastrar novo aluno</h1>

<form action="" method="post" class="container">
  <div class="row mb-3">
    <label class="col-form-label col-2">Nome completo</label>
    <div class="col-10">
      <input class="form-control" name="nome_usuario" required <?php if (isset($_POST["nome_usuario"])) echo "value=\"" . $_POST["nome_usuario"] . "\"" ?> >
    </div>
  </div>
  
  <div class="row mb-3">
    <label class="col-form-label col-2">CPF</label>
    <div class="col-10">
      <input class="form-control" name="cpf" required <?php if (isset($_POST["cpf"])) echo "value=\"" . $_POST["cpf"] . "\"" ?> >
    </div>
  </div>
  
  <div class="row mb-3">
    <label class="col-form-label col-2">Email</label>
    <div class="col-10">
      <input class="form-control" name="email" required <?php if (isset($_POST["email"])) echo "value=\"" . $_POST["email"] . "\"" ?> >
    </div>
  </div>
  
  <div class="row mb-3">
    <label class="col-form-label col-2">Data de nascimento</label>
    <div class="col-10">
      <input class="form-control" type="date" name="data_nasc" required <?php if (isset($_POST["data_nasc"])) echo "value=\"" . $_POST["data_nasc"] . "\"" ?> >
    </div>
  </div>

  <div class="row mb-3">
    <label class="col-form-label col-2">Senha para primeiro acesso</label>
    <div class="col-10">
      <input class="form-control" type="password" name="senha" required <?php if (isset($_POST["senha"])) echo "value=\"" . $_POST["senha"] . "\"" ?> >
    </div>
  </div>

  <div class="row justify-content-center">
    <button type="submit" class="btn btn-primary w-auto">Cadastrar</button>
  </div>
</form>

<?php require_once TEMPLATE_FOOTER ?>
