<?php
  require_once "../../config.php";
  require_once TEMPLATE_HEADER;
?>

<?php
  if ( !isset( $_GET["id_usuario"] ) ) {
    if ( !isset( $_SESSION["id_usuario"] ) ) {
      header("Location: " . BASE_URL);
      exit();
    }
    $usuario["id_usuario"] = $_SESSION["id_usuario"];
    $usuario["nome_usuario"] = $_SESSION["nome_usuario"];
    $usuario["foto"] = $_SESSION["foto"];
    $usuario["data_nasc"] = $_SESSION["data_nasc"];
    $usuario["email"] = $_SESSION["email"];
    $usuario["nivel"] = $_SESSION["nivel"];
    $config = true;
  } else {
    $conn = open_db();
    $id_usuario = mysqli_real_escape_string( $conn, $_GET["id_usuario"] );
    $result = $conn->query("SELECT id_usuario, nome_usuario, foto, data_nasc, email, nivel FROM usuario WHERE id_usuario = $id_usuario");
    close_db($conn);

    if ( $result->num_rows !== 1 ) {
      header("Location: " . BASE_URL);
      exit();
    } else $usuario = $result->fetch_array();

    if (!empty($_SESSION) && ($_GET["id_usuario"] == $_SESSION["id_usuario"])) $config = true;
    else $config = false;
  }
?>

<?php
  if (isset($_POST["acao"]) && $_POST["acao"] == "editar") {
    $conn = open_db();
    if (!empty($_POST["email"])) {
      $email = mysqli_real_escape_string($conn, $_POST["email"]);
      $email = str_replace("\"", "'", $email);
    } else $email = false;
    if ($_FILES["foto"]["error"][0] == 0) $foto = $_FILES["foto"];
    else $foto = false;

    if ($email) {
      $result = $conn->query("UPDATE usuario SET email = '{$email}' WHERE id_usuario = {$_SESSION["id_usuario"]}");
      if ($result) echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Email atualizado com sucesso!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
      else echo $conn->error;
    }
    if ($foto) {
      if (!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"][0])) {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>O arquivo selecionado não é uma imagem!<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        exit();
      }
      $extensao = pathinfo($foto["name"][0], PATHINFO_EXTENSION);
      $nome_imagem = md5(uniqid()) . "." . $extensao;
      $caminho_absoluto_imagem = str_replace("\\", "/", ABS_PATH) . "src/img/perfil/" . $nome_imagem;
      move_uploaded_file($foto["tmp_name"][0], $caminho_absoluto_imagem);
      $caminho_base_imagem = "src/img/perfil/" . $nome_imagem;

      $result = $conn->query("UPDATE usuario SET foto = '{$caminho_base_imagem}' WHERE id_usuario = {$_SESSION["id_usuario"]}");
      if ($result) {
        $result = $conn->query("SELECT foto FROM usuario WHERE id_usuario = {$_SESSION["id_usuario"]}");
        $foto_atualizada = $result->fetch_object();
        $_SESSION["foto"] = $foto_atualizada->foto;
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Foto de perfil atualizada com sucesso! Por favor, faça login novamente para aplicar as modificações.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
      } else echo $conn->error;
    }

    if (!$email && !$foto) echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>Sem dados para atualizar.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
  }
  if (isset($_POST["acao"]) && $_POST["acao"] == "remover") {
    $conn = open_db();
    $result = $conn->query("UPDATE usuario SET foto = NULL WHERE id_usuario = {$_SESSION["id_usuario"]}");
    if ($result) {
      $_SESSION["foto"] = NULL;
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Foto de perfil removida com sucesso! Por favor, faça login novamente para aplicar as modificações.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    } else echo $conn->error;
  }
?>

<div>
  <div class="row justify-content-center">
    <img src="<?php if($usuario["foto"]) echo BASE_URL . $usuario["foto"]; else echo IMG_DEFAULT ?>" style="width: 200px; height: 200px" class="p-0 rounded-circle border border-1 border-dark mb-3">
  </div>
  <div class="row justify-content-center">
    <h1 class="fs-2 text-center"><?php echo $usuario["nome_usuario"] ?></h1>
  </div>
</div>

<div class="container">
  <div class="nav nav-tabs justify-content-center mt-3" id="nav-tab" role="tablist">
    <button class="nav-link text-dark active" id="nav-tab-sobre" data-bs-toggle="tab" data-bs-target="#nav-sobre" type="button" role="tab" aria-controls="nav-sobre" aria-selected="false">Sobre</button>
    <?php if ($config) : ?>
      <button class="nav-link text-dark" id="nav-tab-editar" data-bs-toggle="tab" data-bs-target="#nav-editar" type="button" role="tab" aria-controls="nav-editar" aria-selected="false">Editar perfil</button>
    <?php endif ?>
  </div>

  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade pt-3 show active" id="nav-sobre" role="tabpanel" aria-labelledby="nav-tab-sobre">
      <table class="table w-auto m-auto">
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
          <td><?php echo $usuario["email"] ?></td>
        </tr>
      </table>
    </div>
    <?php if($config) : ?>
      <div class="tab-pane fade pt-3" id="nav-editar" role="tabpanel" aria-labelledby="nav-tab-editar">
        <form action="" method="post" class="container" enctype="multipart/form-data">
          <div class="row mb-3">
            <label class="col-form-label col-2">Email</label>
            <div class="col-10">
              <input class="form-control" name="email" value="<?php echo $usuario["email"] ?>">
            </div>
          </div>
          
          <div class="row mb-3">
            <label class="col-form-label col-2">Foto de perfil</label>
            <div class="col">
              <input class="form-control" type="file" name="foto[]">
            </div>
            <div class="col-2">
              <button type="submit" class="btn btn-danger" name="acao" value="remover">Remover foto</button>
            </div>
          </div>

          <div class="row justify-content-center">
            <button type="submit" class="btn btn-primary w-auto" name="acao" value="editar">Editar</button>
            <a href="<?php echo BASE_URL ?>pages/user/alterar_senha.php" class="btn btn-primary w-auto ms-2">Alterar senha</a>
          </div>
        </form>
      </div>
    <?php endif ?>
  </div>
</div>

<?php require_once TEMPLATE_FOOTER ?>
