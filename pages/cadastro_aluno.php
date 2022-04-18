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
    $data_nasc    = mysqli_real_escape_string( $conn, $_POST["data_nasc"] );
    $senha        = mysqli_real_escape_string( $conn, $_POST["senha"] );

    $result = $conn->query("SELECT * FROM usuario WHERE cpf = $cpf");
    if ($result->num_rows === 1) echo "<div class='alert alert-danger alert-dismissible fade show m-0 mx-auto col-md-9 col-lg-7 col-xl-6 col-xxl-5' role='alert'>Já existe um usuário cadastrado com este CPF<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    else {
      $result = $conn->query("SELECT * FROM usuario WHERE email LIKE '$email'");
      if ($result->num_rows === 1) echo "<div class='alert alert-danger alert-dismissible fade show m-0 mx-auto col-md-9 col-lg-7 col-xl-6 col-xxl-5' role='alert'>Já existe um usuário cadastrado com este Email<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
      else {
        $result = $conn->query("INSERT INTO usuario (cpf, email, data_nasc, nome_usuario, senha, nivel, id_responsavel) VALUES ('$cpf', '$email', '$data_nasc', '$nome_usuario', md5('$senha'), 1, " . $_SESSION["id_usuario"] . ")");
        
        if ($result) echo "<div class='alert alert-success alert-dismissible fade show m-0 mx-auto col-md-9 col-lg-7 col-xl-6 col-xxl-5' role='alert'>Aluno cadastrado com sucesso!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        unset($_POST);
      }
    }
    
    close_db($conn);
  }
?>

<h1 class="fs-2 text-center m-0">Cadastrar novo aluno</h1>

<form action="" method="post" class="row g-0 gap-3 mx-auto col-md-9 col-lg-7 col-xl-6 col-xxl-5">
  <div class="row g-0">
    <label>Nome completo</label>
    <div>
      <input class="form-control" name="nome_usuario" required <?php if (isset($_POST["nome_usuario"])) echo "value=\"" . $_POST["nome_usuario"] . "\"" ?> >
    </div>
  </div>
  
  <div class="row g-0">
    <label>Email</label>
    <div>
      <input class="form-control" name="email" required <?php if (isset($_POST["email"])) echo "value=\"" . $_POST["email"] . "\"" ?> >
    </div>
  </div>
  
  <div class="row g-0 col-sm-6">
    <label>CPF</label>
    <div>
      <input class="form-control" name="cpf" required <?php if (isset($_POST["cpf"])) echo "value=\"" . $_POST["cpf"] . "\"" ?> >
    </div>
  </div>
  
  <div class="row g-0 col">
    <label>Data de nascimento</label>
    <div>
      <input class="form-control" type="date" name="data_nasc" required <?php if (isset($_POST["data_nasc"])) echo "value=\"" . $_POST["data_nasc"] . "\"" ?> >
    </div>
  </div>

  <div class="row g-0">
    <label>Senha para primeiro acesso</label>
    <div>
      <input class="form-control" type="password" name="senha" required <?php if (isset($_POST["senha"])) echo "value=\"" . $_POST["senha"] . "\"" ?> >
    </div>
  </div>

  <button class="btn btn-primary" type="submit">Cadastrar</button>
</form>

<?php require_once TEMPLATE_FOOTER ?>
