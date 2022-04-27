<?php
  require_once "../../config.php";
  require_once TEMPLATE_HEADER;
?>

<?php
  if ( empty($_SESSION) || ( $_SESSION["nivel"] !== "2" && $_SESSION["nivel"] !== "3" ) ) {
    header("Location: " . BASE_URL);
    exit();
  }
?>

<h1 class="fs-2 text-center m-0">Avaliar contribuições</h1>

<div class="container row g-0 gap-3">
  <div class="nav nav-tabs row g-0" id="nav-tab" role="tablist">
    <button class="nav-link text-dark col px-0 active" id="nav-tab-pendentes" data-bs-toggle="tab" data-bs-target="#nav-pendentes" type="button" role="tab" aria-controls="nav-pendentes" aria-selected="true">Pendentes</button>
    <button class="nav-link text-dark col px-0" id="nav-tab-aprovadas" data-bs-toggle="tab" data-bs-target="#nav-aprovadas" type="button" role="tab" aria-controls="nav-aprovadas" aria-selected="false">Aprovadas</button>
    <button class="nav-link text-dark col px-0" id="nav-tab-em-correcao" data-bs-toggle="tab" data-bs-target="#nav-em-correcao" type="button" role="tab" aria-controls="nav-em-correcao" aria-selected="false">Em correção</button>
  </div>

  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-pendentes" role="tabpanel" aria-labelledby="nav-tab-pendentes">
      <div class="row g-0 gap-3">
        <?php
          $conn = open_db();

          $alunos = $conn->query("SELECT id_usuario, nome_usuario FROM usuario WHERE nivel = 1 AND id_responsavel = " . $_SESSION["id_usuario"]);

          if ($alunos->num_rows === 0) echo "<div class='alert alert-secondary m-0'>Você não é responsável por nenhum aluno.</div>";
          else {
            $sem_contribuicoes = true;

            while ($aluno = $alunos->fetch_object()) {
              $contribuicoes = $conn->query("SELECT id_contribuicao, contribuicao, significados FROM contribuicao WHERE id_autor = $aluno->id_usuario AND situacao LIKE 'Pendente'");

              if ($contribuicoes->num_rows !== 0) {
                $sem_contribuicoes = false;

                while ($contribuicao = $contribuicoes->fetch_object()) {
                  echo "
                    <div>
                      <a href='" . BASE_URL . "pages/contribuicoes/avaliar_contribuicao.php?id_contribuicao=" . $contribuicao->id_contribuicao . "' class='nav-link p-0 m-0'>
                        <span class='fw-bold'>" . $contribuicao->contribuicao . "</span><br/>
                      </a>
                      <span>" . $contribuicao->significados . "</span><br/>
                      <span>Por: <a href='" . BASE_URL . "pages/user/perfil.php?id_usuario=" . $aluno->id_usuario . "' class='nav-link d-inline p-0'>" . $aluno->nome_usuario . "</a></span>
                    </div>
                  ";
                }
              }
            }

            if ($sem_contribuicoes) echo "<div class='alert alert-secondary m-0'>Não há contribuições para avaliar.</div>";
          }
          close_db($conn);
        ?>
      </div>
    </div>
    <div class="tab-pane fade" id="nav-aprovadas" role="tabpanel" aria-labelledby="nav-tab-aprovadas">
      <div class="row g-0 gap-3">
        <?php
          $conn = open_db();

          $alunos = $conn->query("SELECT id_usuario, nome_usuario FROM usuario WHERE nivel = 1 AND id_responsavel = " . $_SESSION["id_usuario"]);

          if ($alunos->num_rows === 0) echo "<div class='alert alert-secondary m-0'>Você não é responsável por nenhum aluno.</div>";
          else {
            $sem_contribuicoes = true;

            while ($aluno = $alunos->fetch_object()) {
              $contribuicoes = $conn->query("SELECT id_contribuicao, contribuicao, significados FROM contribuicao WHERE id_autor = $aluno->id_usuario AND situacao LIKE 'Aprovada'");

              if ($contribuicoes->num_rows !== 0) {
                $sem_contribuicoes = false;

                while ($contribuicao = $contribuicoes->fetch_object()) {
                  echo "
                    <div>
                      <a href='" . BASE_URL . "pages/contribuicoes/avaliar_contribuicao.php?id_contribuicao=" . $contribuicao->id_contribuicao . "' class='nav-link p-0 m-0'>
                        <span class='fw-bold'>" . $contribuicao->contribuicao . "</span><br/>
                      </a>
                      <span>" . $contribuicao->significados . "</span><br/>
                      <span>Por: <a href='" . BASE_URL . "pages/user/perfil.php?id_usuario=" . $aluno->id_usuario . "' class='nav-link d-inline p-0'>" . $aluno->nome_usuario . "</a></span>
                    </div>
                  ";
                }
              }
            }

            if ($sem_contribuicoes) echo "<div class='alert alert-secondary m-0'>Não há contribuições aprovadas por você.</div>";
          }
          close_db($conn);
        ?>
      </div>
    </div>
    <div class="tab-pane fade" id="nav-em-correcao" role="tabpanel" aria-labelledby="nav-tab-em-correcao">
      <div class="row g-0 gap-3">
        <?php
          $conn = open_db();

          $alunos = $conn->query("SELECT id_usuario, nome_usuario FROM usuario WHERE nivel = 1 AND id_responsavel = " . $_SESSION["id_usuario"]);

          if ($alunos->num_rows === 0) echo "<div class='alert alert-secondary m-0'>Você não é responsável por nenhum aluno.</div>";
          else {
            $sem_contribuicoes = true;

            while ($aluno = $alunos->fetch_object()) {
              $contribuicoes = $conn->query("SELECT id_contribuicao, contribuicao, significados FROM contribuicao WHERE id_autor = $aluno->id_usuario AND situacao LIKE 'Em correcao'");

              if ($contribuicoes->num_rows !== 0) {
                $sem_contribuicoes = false;

                while ($contribuicao = $contribuicoes->fetch_object()) {
                  echo "
                    <div>
                      <a href='" . BASE_URL . "pages/contribuicoes/avaliar_contribuicao.php?id_contribuicao=" . $contribuicao->id_contribuicao . "' class='nav-link p-0 m-0'>
                        <span class='fw-bold'>" . $contribuicao->contribuicao . "</span><br/>
                      </a>
                      <span>" . $contribuicao->significados . "</span><br/>
                      <span>Por: <a href='" . BASE_URL . "pages/user/perfil.php?id_usuario=" . $aluno->id_usuario . "' class='nav-link d-inline p-0'>" . $aluno->nome_usuario . "</a></span>
                    </div>
                  ";
                }
              }
            }

            if ($sem_contribuicoes) echo "<div class='alert alert-secondary m-0'>Não há contribuições a serem corrigidas pelos alunos.</div>";
          }
          close_db($conn);
        ?>
      </div>
    </div>
  </div>
</div>

<?php require_once TEMPLATE_FOOTER ?>
