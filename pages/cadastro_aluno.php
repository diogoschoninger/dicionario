<?php
  require_once "../config.php";
  require_once TEMPLATE_HEADER;
?>

<?php
  // Verificação de permissão
  if (empty($_SESSION) || ($_SESSION["nivel"] !== "2" && $_SESSION["nivel"] !== "3") ) {
    header("Location: " . BASE_URL);
    exit();
  }
?>

<?php
  if (!empty($_POST)) {
    $conn = open_db();

    // Filtra as entradas de dados
    $nome_usuario = mysqli_real_escape_string( $conn, $_POST["nome_usuario"] );
    $email        = mysqli_real_escape_string( $conn, $_POST["email"] );
    $cpf          = mysqli_real_escape_string( $conn, $_POST["cpf"] );
    $data_nasc    = mysqli_real_escape_string( $conn, $_POST["data_nasc"] );
    $senha        = mysqli_real_escape_string( $conn, $_POST["senha"] );

    // Verifica se já existe um usuário com o mesmo CPF cadastrado
    $results = $conn->query("SELECT * FROM usuario WHERE cpf = $cpf");
    if ($results->num_rows === 1) echo "<div class='alert alert-danger alert-dismissible fade show m-0' role='alert'>Já existe um usuário cadastrado com este CPF<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    else {
      // Verifica se já existe um usuário com o mesmo email cadastrado
      $results = $conn->query("SELECT * FROM usuario WHERE email LIKE '$email'");
      if ($results->num_rows === 1) echo "<div class='alert alert-danger alert-dismissible fade show m-0' role='alert'>Já existe um usuário cadastrado com este Email<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
      else {
        // Realiza o cadastro do usuário no banco de dados
        $results = $conn->query("INSERT INTO usuario (nome_usuario, email, cpf, data_nasc, senha, nivel, id_responsavel) VALUES ('$nome_usuario', '$email',  '$cpf','$data_nasc', md5('$senha'), 1, " . $_SESSION["id_usuario"] . ")");
        
        // Dá o feedback ao usuário
        if ($results) {
          echo "<div class='alert alert-success alert-dismissible fade show m-0' role='alert'>Aluno cadastrado com sucesso!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
          
          // Apaga os dados existentes em $_POST
          unset($_POST);
        } else echo "<div class='alert alert-danger alert-dismissible fade show m-0' role='alert'>" . $conn-> error . "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
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
