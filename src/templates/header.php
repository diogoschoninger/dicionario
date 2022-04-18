<!DOCTYPE html>
<html lang="pt-br" class="h-100">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Dicionário de gírias e palavrões</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <!-- <link href="<?php echo BASE_URL ?>src/bootstrap/bootstrap.min.css" rel="stylesheet"/> -->
</head>

<body class="d-flex flex-column h-100">
  <div class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
      <a href="<?php echo BASE_URL ?>" class="navbar-brand">Dicionário</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <nav class="navbar-nav ms-auto">
          <a href="<?php echo BASE_URL ?>" class="nav-item nav-link <?php if ($url_atual == BASE_URL || $url_atual == (BASE_URL . "index.php")) echo "active" ?>"></i>Pesquisa</a>
          <?php if ( !empty($_SESSION) ) : ?>
            <?php if ( $_SESSION["nivel"] == 1 ) : ?>
              <a href="<?php echo BASE_URL ?>pages/contribuicoes/nova_contribuicao.php" class="nav-item nav-link <?php if ($url_atual == (BASE_URL . "pages/contribuicoes/nova_contribuicao.php")) echo "active" ?>">Nova contribuição</a>
              <a href="<?php echo BASE_URL ?>pages/contribuicoes/minhas_contribuicoes.php" class="nav-item nav-link <?php if ($url_atual == (BASE_URL . "pages/contribuicoes/minhas_contribuicoes.php")) echo "active" ?>">Minhas contribuições</a>
            <?php elseif ( $_SESSION["nivel"] == 2 || $_SESSION["nivel"] == 3 ) : ?>
              <a href="<?php echo BASE_URL ?>pages/contribuicoes/avaliar_contribuicoes.php" class="nav-item nav-link <?php if ($url_atual == (BASE_URL . "pages/contribuicoes/avaliar_contribuicoes.php")) echo "active" ?>">Avaliar contribuições</a>
              <a href="<?php echo BASE_URL ?>pages/cadastro_aluno.php" class="nav-item nav-link <?php if ($url_atual == (BASE_URL . "pages/cadastro_aluno.php")) echo "active" ?>">Cadastrar novo aluno</a>
              <a href="<?php echo BASE_URL ?>pages/meus_alunos.php" class="nav-item nav-link <?php if ($url_atual == (BASE_URL . "pages/meus_alunos.php")) echo "active" ?>">Meus alunos</a>
            <?php endif ?>
            
            <?php if ( $_SESSION["nivel"] == 3 ) : ?>
              <a href="<?php echo BASE_URL ?>pages/cadastro_professor.php" class="nav-item nav-link <?php if ($url_atual == (BASE_URL . "pages/cadastro_professor.php")) echo "active" ?>">Cadastrar novo professor</a>
            <?php endif ?>
          <?php endif ?>

          <?php if ( isset( $_SESSION["id_usuario"] ) ) : ?>
            <div class="nav-item dropdown">
              <a href="<?php echo BASE_URL ?>" class="nav-link dropdown-toggle p-1" id="dropdownUserSidebar" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?php if($_SESSION["foto"]) echo BASE_URL . $_SESSION["foto"]; else echo IMG_DEFAULT ?>" style="width: 32px; height: 32px; object-fit: cover" class="rounded-circle">
              </a>
              <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUserSidebar">
                <li><a class="dropdown-item" href="<?php echo BASE_URL ?>pages/user/perfil.php">Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?php echo BASE_URL ?>pages/session/logout.php">Sair</a></li>
              </ul>
            </div>
          <?php else : ?>
            <a href="<?php echo BASE_URL ?>pages/session/login.php" class="nav-item nav-link <?php if ($url_atual == (BASE_URL . "pages/session/login.php")) echo "active" ?>">Login</a>
          <?php endif ?>
        </nav>
      </div>
    </div>
  </div>

  <div class="container p-3">
    <div class="row g-0 gap-3">
