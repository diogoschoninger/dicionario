<?php
  require_once "../../config.php";
  require_once TEMPLATE_HEADER;
?>

<?php
  if (empty($_SESSION) || $_SESSION["nivel"] != 1) {
    header("Location: " . BASE_URL);
    exit();
  }
?>

<div class="container">
  <div class="nav nav-tabs justify-content-center mt-3" id="nav-tab" role="tablist">
    <button class="nav-link text-dark active" id="nav-tab-contribuicoes-aprovadas" data-bs-toggle="tab" data-bs-target="#nav-contribuicoes-aprovadas" type="button" role="tab" aria-controls="nav-contribuicoes-aprovadas" aria-selected="true">Contribuições  aprovadas</button>
    <button class="nav-link text-dark" id="nav-tab-contribuicoes-pendentes" data-bs-toggle="tab" data-bs-target="#nav-contribuicoes-pendentes" type="button" role="tab" aria-controls="nav-contribuicoes-pendentes" aria-selected="false">Contribuições pendentes de avaliação</button>
    <button class="nav-link text-dark" id="nav-tab-contribuicoes-em-correcao" data-bs-toggle="tab" data-bs-target="#nav-contribuicoes-em-correcao" type="button" role="tab" aria-controls="nav-contribuicoes-em-correcao" aria-selected="false">Contribuições para corrigir</button>
  </div>

  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-contribuicoes-aprovadas" role="tabpanel" aria-labelledby="nav-tab-contribuicoes-aprovadas">
      <table class="table table-sm">
        <tbody>
          <?php
            $conn = open_db();
            $result = $conn->query("SELECT id_contribuicao, contribuicao, significados FROM contribuicao WHERE id_autor = {$_SESSION["id_usuario"]} AND situacao LIKE 'Aprovada'");
            close_db($conn);
            
            while ( $contribuicao = $result->fetch_object() ) :
          ?>
            <tr>
              <td>
                <a href="<?php echo BASE_URL ?>pages/contribuicoes/detalhes_contribuicao.php?id_contribuicao=<?php echo $contribuicao->id_contribuicao ?>" class="nav-link px-0">
                  <span class="fw-bold"><?php echo $contribuicao->contribuicao ?></span><br/>
                </a>
                <span><?php echo $contribuicao->significados ?></span><br/>
              </td>
            </tr>
          <?php endwhile ?>

          <?php if ($result->num_rows === 0) : ?>
            <p class='fs-5 mt-3 text-center'>Você não possui contribuições aprovadas.</p>
          <?php endif ?>
        </tbody>
      </table>
    </div>

    <div class="tab-pane fade" id="nav-contribuicoes-pendentes" role="tabpanel" aria-labelledby="nav-tab-contribuicoes-pendentes">
      <table class="table table-sm">
        <tbody>
          <?php
            $conn = open_db();
            $result = $conn->query("SELECT id_contribuicao, contribuicao, significados FROM contribuicao WHERE id_autor = {$_SESSION["id_usuario"]} AND situacao LIKE 'Pendente'");
            close_db($conn);
            
            while ( $contribuicao = $result->fetch_object() ) :
          ?>
            <tr>
              <td>
                <a href="<?php echo BASE_URL ?>pages/contribuicoes/detalhes_contribuicao.php?id_contribuicao=<?php echo $contribuicao->id_contribuicao ?>" class="nav-link px-0">
                  <span class="fw-bold"><?php echo $contribuicao->contribuicao ?></span><br/>
                </a>
                <span><?php echo $contribuicao->significados ?></span><br/>
              </td>
            </tr>
          <?php endwhile ?>
        
          <?php if ($result->num_rows === 0) : ?>
            <p class='fs-5 mt-3 text-center'>Você não possui contribuições aguardando avaliação.</p>
          <?php endif ?>
        </tbody>
      </table>
    </div>

    <div class="tab-pane fade" id="nav-contribuicoes-em-correcao" role="tabpanel" aria-labelledby="nav-tab-contribuicoes-em-correcao">
      <table class="table table-sm">
        <tbody>
          <?php
            $conn = open_db();
            $result = $conn->query("SELECT id_contribuicao, contribuicao, significados FROM contribuicao WHERE id_autor = {$_SESSION["id_usuario"]} AND situacao LIKE 'Em correcao'");
            close_db($conn);
            
            while ( $contribuicao = $result->fetch_object() ) :
          ?>
            <tr>
              <td>
                <a href="<?php echo BASE_URL ?>pages/contribuicoes/corrigir_contribuicao.php?id_contribuicao=<?php echo $contribuicao->id_contribuicao ?>" class="nav-link px-0">
                  <span class="fw-bold"><?php echo $contribuicao->contribuicao ?></span><br/>
                </a>
                <span><?php echo $contribuicao->significados ?></span><br/>
              </td>
            </tr>
          <?php endwhile ?>
        
          <?php if ($result->num_rows === 0) : ?>
            <p class='fs-5 mt-3 text-center'>Você não possui nenhuma contribuição para refazer.</p>
          <?php endif ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once TEMPLATE_FOOTER ?>
