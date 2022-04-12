<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Título da página</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="<?php echo BASE_URL ?>src/css/main.css"/>
</head>

<body class="grid-template-area">
  <div class="d-flex flex-column flex-shrink-0 bg-dark p-3 sticky-top" id="sidebar">
    <a href="<?php echo BASE_URL ?>" class="nav-link fs-3 text-white text-center">Dicionário</a>

    <hr class="text-white">

    <div class="nav nav-pills flex-column mb-auto">
      <a href="<?php echo BASE_URL ?>" class="nav-item nav-link text-white fs-5 <?php if ($url_atual == BASE_URL || $url_atual == (BASE_URL . "index.php")) echo "active" ?>"></i>Pesquisa</a>

      <?php if ( !empty($_SESSION) ) : ?>
        <?php if ( $_SESSION["nivel"] == 1 ) : ?>
          <a href="<?php echo BASE_URL ?>pages/contribuicoes/nova_contribuicao.php" class="nav-link text-white fs-5 <?php if ($url_atual == (BASE_URL . "pages/contribuicoes/nova_contribuicao.php")) echo "active" ?>">Nova contribuição</a>
          <a href="<?php echo BASE_URL ?>pages/contribuicoes/minhas_contribuicoes.php" class="nav-link text-white fs-5 <?php if ($url_atual == (BASE_URL . "pages/contribuicoes/minhas_contribuicoes.php")) echo "active" ?>">Minhas contribuições</a>
        <?php elseif ( $_SESSION["nivel"] == 2 || $_SESSION["nivel"] == 3 ) : ?>
          <a href="<?php echo BASE_URL ?>pages/contribuicoes/avaliar_contribuicoes.php" class="nav-link text-white fs-5 <?php if ($url_atual == (BASE_URL . "pages/contribuicoes/avaliar_contribuicoes.php")) echo "active" ?>">Avaliar contribuições</a>
          <a href="<?php echo BASE_URL ?>pages/cadastro_aluno.php" class="nav-link text-white fs-5 <?php if ($url_atual == (BASE_URL . "pages/cadastro_aluno.php")) echo "active" ?>">Cadastrar novo aluno</a>
          <a href="<?php echo BASE_URL ?>pages/meus_alunos.php" class="nav-link text-white fs-5 <?php if ($url_atual == (BASE_URL . "pages/meus_alunos.php")) echo "active" ?>">Meus alunos</a>
        <?php endif ?>
        
        <?php if ( $_SESSION["nivel"] == 3 ) : ?>
          <a href="<?php echo BASE_URL ?>pages/cadastro_professor.php" class="nav-link text-white fs-5 <?php if ($url_atual == (BASE_URL . "pages/cadastro_professor.php")) echo "active" ?>">Cadastrar novo professor</a>
        <?php endif ?>
      <?php endif ?>
    </div>

    <hr class="text-white">

    <?php if ( isset( $_SESSION["id_usuario"] ) ) : ?>
      <div class="dropdown">
        <a href="<?php echo BASE_URL ?>" class="d-flex align-items-center text-decoration-none dropdown-toggle text-white" id="dropdownUserSidebar" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="<?php if($_SESSION["foto"]) echo BASE_URL . $_SESSION["foto"]; else echo IMG_DEFAULT ?>" width="32" height="32" class="rounded-circle me-2">
          <span class="text-white"><?php echo $_SESSION["nome_usuario"] ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUserSidebar">
          <li><a class="dropdown-item" href="<?php echo BASE_URL ?>pages/user/perfil.php">Perfil</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="<?php echo BASE_URL ?>pages/session/logout.php">Sair</a></li>
        </ul>
      </div>
    <?php else : ?>
      <a href="<?php echo BASE_URL ?>pages/session/login.php" class="btn btn-primary">Login</a>
    <?php endif ?>
  </div>

  <div id="content" class="p-3">
