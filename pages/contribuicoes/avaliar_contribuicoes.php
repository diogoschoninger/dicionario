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

<h1 class="fs-2 text-center">Avaliar contribuições</h1>

<div class="container">
  <div class="nav nav-tabs justify-content-center mt-3" id="nav-tab" role="tablist">
    <button class="nav-link text-dark active" id="nav-tab-pendentes" data-bs-toggle="tab" data-bs-target="#nav-pendentes" type="button" role="tab" aria-controls="nav-pendentes" aria-selected="true">Pendentes</button>
    <button class="nav-link text-dark" id="nav-tab-aprovadas" data-bs-toggle="tab" data-bs-target="#nav-aprovadas" type="button" role="tab" aria-controls="nav-aprovadas" aria-selected="false">Aprovadas</button>
    <button class="nav-link text-dark" id="nav-tab-em-correcao" data-bs-toggle="tab" data-bs-target="#nav-em-correcao" type="button" role="tab" aria-controls="nav-em-correcao" aria-selected="false">Em correção</button>
  </div>

  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-pendentes" role="tabpanel" aria-labelledby="nav-tab-pendentes">
      <table class="table table-sm">
        <tbody>
          <?php
            $conn = open_db();
            $result = $conn->query("SELECT id_usuario, nome_usuario FROM usuario WHERE nivel = 1 AND id_responsavel = " . $_SESSION["id_usuario"]);

            if ($result->num_rows === 0) echo "<p class='fs-5 mt-3 text-center'>Você ainda não é responsável por nenhum aluno</p>";
            else {
              $sem_contribuicoes = true;

              while ($usuarios = $result->fetch_object()) {
                $result2 = $conn->query("SELECT id_contribuicao, contribuicao, significados, id_autor FROM contribuicao WHERE id_autor = $usuarios->id_usuario AND situacao LIKE 'Pendente'");

                if ($result2->num_rows !== 0) {
                  $sem_contribuicoes = false;

                  while ($contribuicoes = $result2->fetch_object()) {
                    echo "
                    <tr>
                    <td>
                    <a href=" . BASE_URL . "pages/contribuicoes/avaliar_contribuicao.php?id_contribuicao=" . $contribuicoes->id_contribuicao . " class=\"nav-link px-0\">
                    <span class=\"fw-bold\">" . $contribuicoes->contribuicao . "</span><br/>
                    </a>
                    <span>" . $contribuicoes->significados . "</span><br/>
                    <span>Por: <a href=\"" . BASE_URL . "pages/user/perfil.php?id_usuario=" . $contribuicoes->id_autor . "\" class=\"nav-link d-inline px-0\">" . $usuarios->nome_usuario . "</a></span>
                    </td>
                    </tr>
                    ";
                  }
                }
              }

              if ($sem_contribuicoes) echo "<p class='fs-5 mt-3 text-center'>No momento não há contribuições pendentes de aprovação.</p>";
            }

            close_db($conn);
          ?>
        </tbody>
      </table>
    </div>
    <div class="tab-pane fade" id="nav-aprovadas" role="tabpanel" aria-labelledby="nav-tab-aprovadas">
      <table class="table table-sm">
        <tbody>
          <?php
            $conn = open_db();
            $result = $conn->query("SELECT id_usuario, nome_usuario FROM usuario WHERE nivel = 1 AND id_responsavel = " . $_SESSION["id_usuario"]);

            if ($result->num_rows === 0) echo "<p class='fs-5 mt-3 text-center'>Você ainda não é responsável por nenhum aluno</p>";
            else {
              $sem_contribuicoes = true;

              while ($usuarios = $result->fetch_object()) {
                $result2 = $conn->query("SELECT id_contribuicao, contribuicao, significados, id_autor FROM contribuicao WHERE id_autor = $usuarios->id_usuario AND situacao LIKE 'Aprovada'");

                if ($result2->num_rows !== 0) {
                  $sem_contribuicoes = false;

                  while ($contribuicoes = $result2->fetch_object()) {
                    echo "
                    <tr>
                    <td>
                    <a href=" . BASE_URL . "pages/contribuicoes/avaliar_contribuicao.php?id_contribuicao=" . $contribuicoes->id_contribuicao . " class=\"nav-link px-0\">
                    <span class=\"fw-bold\">" . $contribuicoes->contribuicao . "</span><br/>
                    </a>
                    <span>" . $contribuicoes->significados . "</span><br/>
                    <span>Por: <a href=\"" . BASE_URL . "pages/user/perfil.php?id_usuario=" . $contribuicoes->id_autor . "\" class=\"nav-link d-inline px-0\">" . $usuarios->nome_usuario . "</a></span>
                    </td>
                    </tr>
                    ";
                  }
                }
              }

              if ($sem_contribuicoes) echo "<p class='fs-5 mt-3 text-center'>No momento não há contribuições aprovadas.</p>";
            }

            close_db($conn);
          ?>
        </tbody>
      </table>
    </div>
    <div class="tab-pane fade" id="nav-em-correcao" role="tabpanel" aria-labelledby="nav-tab-em-correcao">
      <table class="table table-sm">
        <tbody>
          <?php
            $conn = open_db();
            $result = $conn->query("SELECT id_usuario, nome_usuario FROM usuario WHERE nivel = 1 AND id_responsavel = " . $_SESSION["id_usuario"]);

            if ($result->num_rows === 0) echo "<p class='fs-5 mt-3 text-center'>Você ainda não é responsável por nenhum aluno</p>";
            else {
              $sem_contribuicoes = true;

              while ($usuarios = $result->fetch_object()) {
                $result2 = $conn->query("SELECT id_contribuicao, contribuicao, significados, id_autor FROM contribuicao WHERE id_autor = $usuarios->id_usuario AND situacao LIKE 'Em correcao'");

                if ($result2->num_rows !== 0) {
                  $sem_contribuicoes = false;

                  while ($contribuicoes = $result2->fetch_object()) {
                    echo "
                    <tr>
                    <td>
                    <a href=" . BASE_URL . "pages/contribuicoes/avaliar_contribuicao.php?id_contribuicao=" . $contribuicoes->id_contribuicao . " class=\"nav-link px-0\">
                    <span class=\"fw-bold\">" . $contribuicoes->contribuicao . "</span><br/>
                    </a>
                    <span>" . $contribuicoes->significados . "</span><br/>
                    <span>Por: <a href=\"" . BASE_URL . "pages/user/perfil.php?id_usuario=" . $contribuicoes->id_autor . "\" class=\"nav-link d-inline px-0\">" . $usuarios->nome_usuario . "</a></span>
                    </td>
                    </tr>
                    ";
                  }
                }
              }

              if ($sem_contribuicoes) echo "<p class='fs-5 mt-3 text-center'>No momento não há contribuições em correção.</p>";
            }

            close_db($conn);
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once TEMPLATE_FOOTER ?>
