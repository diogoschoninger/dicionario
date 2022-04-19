<?php
  require_once "../../config.php";
  require_once TEMPLATE_HEADER;
?>

<?php
  if (!isset($_GET["id_usuario"])) {
    if (!isset($_SESSION["id_usuario"])) {
      header("Location: " . BASE_URL);
      exit();
    }
    $usuario["id_usuario"]   = $_SESSION["id_usuario"];
    $usuario["nome_usuario"] = $_SESSION["nome_usuario"];
    $usuario["data_nasc"]    = $_SESSION["data_nasc"];
    $usuario["email"]        = $_SESSION["email"];
    $usuario["foto"]         = $_SESSION["foto"];
    $usuario["nivel"]        = $_SESSION["nivel"];
    $config = true;
  } else {
    $conn = open_db();
    $id_usuario = mysqli_real_escape_string($conn, $_GET["id_usuario"]);
    $result = $conn->query("SELECT id_usuario, nome_usuario, data_nasc, email, foto, nivel FROM usuario WHERE id_usuario = $id_usuario");
    close_db($conn);

    if ($result->num_rows !== 1) {
      header("Location: " . BASE_URL);
      exit();
    } else $usuario = $result->fetch_array();

    if (!empty($_SESSION) && ($_GET["id_usuario"] == $_SESSION["id_usuario"])) $config = true;
    else $config = false;
  }
?>

<?php
  // Se o usuário clicar no botão "Editar"
  if (isset($_POST["acao"]) && $_POST["acao"] == "editar") {
    $conn = open_db();
    
    // Filtra a entrada de email
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $email = str_replace("\"", "'", $email);
    
    // Verifica erros no upload de imagem e se foi feito upload de alguma imagem
    $foto = $_FILES["foto"]["error"][0] == 0 ? $_FILES["foto"] : false;

    // Verifica diferenças entre o email cadastrado e o novo email
    $results = $conn->query("SELECT email FROM usuario WHERE id_usuario = {$_SESSION["id_usuario"]}");
    $result = $results->fetch_object();
    if ($email !== $result->email) {
      $results = $conn->query("UPDATE usuario SET email = $email WHERE id_usuario = {$_SESSION['id_usuario']}");
      if ($results) {
        echo "<div class='alert alert-success alert-dismissible fade show m-0' role='alert'>Email atualizado com sucesso!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
      } else {
        echo "<div class='alert alert-danger alert-dismissible fade show m-0' role='alert'>" . $results->error . "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
      }
    } else $email = false;

    // Atualiza a foto de perfil, caso a mesma tenha sido enviada
    if ($foto) {
      // Verifica se o arquivo enviado é uma imagem
      if (!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"][0])) {
        echo "<div class='alert alert-danger alert-dismissible fade show m-0' role='alert'>O arquivo enviado não é uma imagem!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        exit();
      }

      // Altera o nome da imagem, move para o diretório específico e salva o caminho base para posterior salvamento no banco de dados
      $ext = pathinfo($foto["name"][0], PATHINFO_EXTENSION);
      $nome_imagem = md5(uniqid()) . "." . $ext;
      $image_abs_path = str_replace("\\", "/", ABS_PATH) . "src/img/perfil/" . $nome_imagem;
      move_uploaded_file($foto["tmp_name"][0], $image_abs_path);
      $image_base_path = "src/img/perfil/" . $nome_imagem;

      // Faz o salvamento do caminho da imagem no banco de dados
      $results = $conn->query("UPDATE usuario SET foto = '{$image_base_path}' WHERE id_usuario = {$_SESSION["id_usuario"]}");
      if ($results) {
        // Apaga a foto anterior do diretório
        if ($_SESSION["foto"]) unlink(str_replace("\\", "/", ABS_PATH) . $_SESSION["foto"]);

        // Busca o caminho da foto atualizada no banco de dados e atribui às variáveis resposáveis
        $results = $conn->query("SELECT foto FROM usuario WHERE id_usuario = {$_SESSION["id_usuario"]}");
        $foto_atualizada = $results->fetch_object();
        $_SESSION["foto"] = $foto_atualizada->foto;
        $usuario["foto"] = $foto_atualizada->foto;

        // Exibe o feedback ao usuário
        echo "<div class='alert alert-success alert-dismissible fade show m-0' role='alert'>Foto de perfil atualizada com sucesso! Faça login novamente para aplicar as mudanças.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
      } else {
        // Exibe erro caso não seja possível atualizar a foto do usuário
        echo "<div class='alert alert-danger alert-dismissible fade show m-0' role='alert'>" . $results->error . "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
      }
    }

    if (!$email && !$foto) {
      echo "<div class='alert alert-secondary alert-dismissible fade show m-0' role='alert'>Sem dados para atualizar.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
  }

  // Se o usuário clicar no botão "Remover foto"
  if (isset($_POST["acao"]) && $_POST["acao"] == "remover") {
    $conn = open_db();

    // Adiciona NULL à coluna foto no banco de dados
    $results = $conn->query("UPDATE usuario SET foto = NULL WHERE id_usuario = {$_SESSION["id_usuario"]}");
    if ($results) {
      // Em caso de sucesso no update, apaga o arquivo da foto removida e atribui NULL às variáveis responsáveis pela foto de perfil
      unlink(str_replace("\\", "/", ABS_PATH) . $_SESSION["foto"]);
      $_SESSION["foto"] = null;
      $usuario["foto"] = null;

      // Exibe o feedback ao usuário
      echo "<div class='alert alert-success alert-dismissible fade show m-0' role='alert'>Foto de perfil removida com sucesso! Faça login novamente para aplicar as mudanças.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } else {
      // Exibe erro caso não seja possível realizar o comando no banco de dados
      echo "<div class='alert alert-danger alert-dismissible fade show m-0' role='alert'>" . $results->error . "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
  }
?>

<div class="row g-0 gap-3">
  <img class="row g-0 mx-auto rounded-circle" src="<?php if($usuario["foto"]) echo BASE_URL . $usuario["foto"]; else echo IMG_DEFAULT ?>" style="width: 12rem; height: 12rem; object-fit: cover">
  <h1 class="row g-0 justify-content-center fs-2 m-0"><?php echo $usuario["nome_usuario"] ?></h1>
</div>

<div class="row g-0 gap-3">
  <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
    <button class="nav-link text-dark active" id="nav-tab-sobre" data-bs-toggle="tab" data-bs-target="#nav-sobre" type="button" role="tab" aria-controls="nav-sobre" aria-selected="false">Sobre</button>
    <?php if ($config) : ?>
      <button class="nav-link text-dark" id="nav-tab-editar" data-bs-toggle="tab" data-bs-target="#nav-editar" type="button" role="tab" aria-controls="nav-editar" aria-selected="false">Editar perfil</button>
    <?php endif ?>
  </div>

  <div class="tab-content col-auto mx-auto" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-sobre" role="tabpanel" aria-labelledby="nav-tab-sobre">
      <table class="table m-0">
        <tr>
          <th>Nome</th>
          <td><?php echo $usuario["nome_usuario"] ?></td>
        </tr>
        <tr>
          <th>Idade</th>
          <?php
            $data = explode( "-", $usuario["data_nasc"] );
            $ano_nasc = $data[0];
            $mes_nasc = $data[1];
            $dia_nasc = $data[2];
            $ano_atual = date("Y");
            $mes_atual = date("m");
            $dia_atual = date("d");

            $usuario["idade"] = $ano_atual - $ano_nasc;

            if ( ($mes_atual < $mes_nasc) || ( ($mes_atual == $mes_nasc) && ($dia_atual <= $dia_nasc) ) ) $usuario["idade"] -= 1;
          ?>
          <td><?php echo $usuario["idade"] ?></td>
        </tr>
        <tr>
          <th>Email</th>
          <td class="text-break"><?php echo $usuario["email"] ?></td>
        </tr>
      </table>
    </div>
    <?php if($config) : ?>
      <div class="tab-pane fade" id="nav-editar" role="tabpanel" aria-labelledby="nav-tab-editar">
        <form class="row g-0 gap-3 col-md-9 col-lg-7 col-xl-6 mx-auto" action="" method="post" enctype="multipart/form-data">
          <div class="row g-0">
            <label>Email</label>
            <input class="form-control" type="email" required name="email" value="<?php echo $usuario["email"] ?>">
          </div>
          
          <div class="row g-0">
            <label>Foto de perfil</label>
            <div class="row g-0 gap-3">
              <input class="form-control col" type="file" name="foto[]">
              <button class="btn btn-danger col-auto" type="submit" name="acao" value="remover">Remover foto</button>
            </div>
          </div>

          <a class="row g-0 btn btn-primary" href="<?php echo BASE_URL ?>pages/user/alterar_senha.php">Alterar senha</a>

          <button class="row g-0 btn btn-success" type="submit" name="acao" value="editar">Editar</button>
        </form>
      </div>
    <?php endif ?>
  </div>
</div>

<?php require_once TEMPLATE_FOOTER ?>
