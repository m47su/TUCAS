<?php
      require("../conn.php");
      $tabela = $pdo->prepare("SELECT id_chave, nome_chave, vista_chave, data_chave, disponivel_chave, responsavel
      FROM chaves;");
      $tabela->execute();
      $rowTabela = $tabela->fetchAll();
      
      /*
      if (empty($rowTabela)){
          echo "<script>
          alert('Tabela vazia!!!');
          </script>";
      } */
      if (isset($_SESSION['id'])) {
          $usuarioLogadoId = $_SESSION['id'];

      /*Barra de pesquisa*/
    }
    if(!empty($_GET['search'])){
      $search = $_GET['search'];
      $tabela = $pdo->prepare("SELECT id_chave, nome_chave, vista_chave, data_chave, disponivel_chave, responsavel
      FROM chaves WHERE id_chave = '$search' OR nome_chave LIKE '%$search%' OR vista_chave LIKE '%$search%' OR data_chave LIKE '%$search%' OR responsavel LIKE '%$search%';");
      $tabela->execute();
      $rowTabela = $tabela->fetchAll();
  }
  else{
      $tabela = $pdo->prepare("SELECT id_chave, nome_chave, vista_chave, data_chave, disponivel_chave, responsavel
      FROM chaves ORDER BY id_chave DESC;");
      $tabela->execute();
      $rowTabela = $tabela->fetchAll();
  }

  ?>
  <!DOCTYPE HTML>
  <html lang="pt-br">
  <head>
          <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
          <title>Gráfico de Produtos no Estoque</title>
          <link rel="shortcut icon" href="imagens/tucano.png" type="image/png">
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> 
          <link rel="stylesheet" href="../styleSideBar.css"> 
          <link rel="stylesheet" href="../stylePag.css">
          <script src="https://kit.fontawesome.com/a5a5afe9dc.js" crossorigin="anonymous"></script>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/icons/bootstrap-icons.min.css" rel="stylesheet">
          <link rel="stylesheet" href="styleTabela.css">
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
          <style>
          .box-search{
            display: flex;
            justify-content: flex-start;
            gap: .1%;
        }
    </style>
        </head>
      <body>
        
      
      <!-- GRAFICO -->
      <?php
// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "estoquerenisson";

try {
    // Criação da conexão PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obter os valores da tabela
    $sql = "SELECT produto_empr, quantidade_empr FROM empr_pr";
    $stmt = $conn->query($sql);

    $dadosGastos = [];
    $labels = [];

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $labels[] = $row['produto_empr'];
            $dadosGastos[] = $row['quantidade_empr'];
        }
    }

    // Fecha a conexão PDO
    $conn = null;
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}
?>
          <div class="container">

              <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logout a {
            background-color: #F2C063;
            color: #F2E6CE;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-right: 60px;
        }

        .grafico-container {
            margin-top: 250px;
            width: 800px;
            height: 600px;
            margin-left: 350px;
        }
    </style>
</head>

<body>
    <div class="grafico-container">
        <canvas id="canvasGrafico"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.onload = function () {
            var ctx = document.getElementById("canvasGrafico").getContext("2d");

            // Dados dos gastos por produto
            var dadosGastos = <?php echo json_encode($dadosGastos); ?>;
            var labels = <?php echo json_encode($labels); ?>;

            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Quantidade de Saídas (Produtos retornáveis)",
                        data: dadosGastos,
                        backgroundColor: "rgba(255, 0, 0, 0.2)", // Fundo vermelho
                        borderColor: "rgba(255, 0, 0, 1)", // Borda vermelha
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        };
    </script>
</body>

  <!-- SIDEBAR -->
  <div class="barraTop">
  <div class="logo">

        <span></span>
      </button>
      <button type="button" onclick="toggleSidebar()"class="toggle toggles" id="toggles">
        <span onclick="toggleSidebar()"></span>
      </button>

              <img src="../imagens/tucano.png" alt="Logo Icon" class="logo-icon">
          </a>

              <span class="logo-text">Tucas</span>
          </div>
          
          <div class="logout">
              <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
          </div>

      </div>




    <div class="sidebar">
    <script>
    function toggleSidebar() {
    var sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('sidebar-closed');
  }
    </script>
      <ul class="custom-list">
        <li>.</li>
        <li>.</li>
        <li>.</li>
        
        <li class="nav-item-bobo">
          <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#menu_item2" href="#"> Produtos de uso único <i class="bi small bi-caret-down-fill h3"></i> </a>
          <ul id="menu_item2" class="submenu collapse" data-bs-parent="#nav_accordion">
                
                <li class="nav-item-baba">
                    <a class="nav-link tabelaPrincipal"  onclick="redirecionarParaPaginaTabelas()" href="#"><i class="fas fa-search"></i> Tabela </a>                  
                </li>
                <script>

      function redirecionarParaPaginaTabelas() {
          window.location.href = "tabelas.php";
      }
  </script> 

                <li class="nav-item-baba">
                    <a onclick="redirecionarParaPaginaHistorico()" hrfe="tabelasEMPR.php" class="nav-link tabelaHistorico" href="#"><i class="far fa-clock"></i> Histórico </a>
                </li>
  <script>
      function redirecionarParaPaginaHistorico() {
          window.location.href = "tabelasEMPR.php";
      }
  </script> 

  <li class="nav-item-baba">
                    <a onclick="redirecionarParaPaginaBaixas()" hrfe="tabelasBAIXAS.php" class="nav-link tabelaBaixas" href="#"><i class="fas fa-arrow-circle-down"></i> Baixas </a>
                </li>
  <script>
      function redirecionarParaPaginaBaixas() {
          window.location.href = "tabelasBAIXAS.php";
      }
  </script>  

          </ul>
      </li>   
      
      <li class="nav-item-bobo">
          <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#menu_item2" href="#"> Gráficos <i class="bi small bi-caret-down-fill h3"></i> </a>
          <ul id="menu_item2" class="submenu collapse" data-bs-parent="#nav_accordion">
                
                <li class="nav-item-baba">
                    <a class="nav-link tabelaPrincipal"  onclick="redirecionarParaPaginaTabelas()" href="#"><i class="fas fa-search"></i> Gráficos </a>                  
                </li>
                <script>

      function redirecionarParaPaginaTabelas() {
          window.location.href = "graficoTotal.php";
      }
  </script> 

          </ul>
      </li>   

      

      
      <li class="nav-item-bobo">
          <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#menu_item2" href="#"> Produtos retornáveis <i class="bi small bi-caret-down-fill h3"></i> </a>
          <ul id="menu_item2" class="submenu collapse" data-bs-parent="#nav_accordion">
                
          </li>
                <li class="nav-item-baba">
                    <a onclick="redirecionarParaPaginaPR()" hrfe="tabelasPR.php" class="nav-link tabelaHistorico" href="#"><i class="fas fa-search"></i> Tabela</a>
                </li>
  <script>
      function redirecionarParaPaginaPR() {
          window.location.href = "tabelasPR.php";
      }
  </script>    

                <li class="nav-item-baba">
                    <a onclick="redirecionarParaHistoricoPR()" hrfe="tabelasPR.php" class="nav-link tabelaHistoricoPR" href="#"><i class="far fa-clock"></i> Histórico </a>
                </li>
  <script>
      function redirecionarParaHistoricoPR() {
          window.location.href = "historicoPR.php";
      }
  </script>

                <li class="nav-item-baba">
                    <a onclick="redirecionarParaBaixasPR()" hrfe="tabelasPR.php" class="nav-link tabelaHistorico" href="#"><i class="fas fa-arrow-circle-down"></i> Baixas <a>
                </li>
  <script>
      function redirecionarParaBaixasPR() {
          window.location.href = "baixasPR.php";
      }
  </script>    

          </ul>
      </li>     

      <li class="nav-item-bobo">
          <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#menu_item2" href="#"> Chaves <i class="bi small bi-caret-down-fill h3"></i> </a>
          <ul id="menu_item2" class="submenu collapse" data-bs-parent="#nav_accordion">
                
          </li>
                <li class="nav-item-baba">
                    <a onclick="redirecionarParaPaginaChaves()"style="opacity:50%;" hrfe="tabelasPR.php" class="nav-link tabelaHistorico" href="#"><i class="fas fa-key"></i> Chaves</a>
                </li>
  <script>
      function redirecionarParaPaginaChaves() {
          window.location.href = "tabelasCHAVE.php";
      }
  </script>     

          </ul>
      </li>         

      </ul>

      

    <script type="text/javascript">

  document.addEventListener("DOMContentLoaded", function(){

    document.querySelectorAll('.sidebar .nav-link').forEach(function(element){

      element.addEventListener('click', function (e) {

        let nextEl = element.nextElementSibling;
        let parentEl  = element.parentElement;	

        if(nextEl) {
          e.preventDefault();	
          let mycollapse = new bootstrap.Collapse(nextEl);

            if(nextEl.classList.contains('show')){
              mycollapse.hide();
            } else {
              mycollapse.show();
              // find other submenus with class=show
              var opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
              // if it exists, then close all of them
            if(opened_submenu){
              new bootstrap.Collapse(opened_submenu);
            }

            }
          }

      });
    })

  }); 
  // DOMContentLoaded  end
  </script>


                                          <!-- SIDEBAR FIM -->

    <script src="script.js"></script>

 








          
          </table>
            <!-- Modal EMPRESTAR -->

  <div class="modal fade" id="emprestarModalLabel" tabindex="9999" role="dialog" aria-labelledby="emprestarModalLabel" aria-hidden="true">

<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="emprestarModalLabel">Emprestar chave</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
    <form action="CRUDE/emprestar_chave.php" method="POST">
        <div class="form-group">
          <label for="nome">Para quem você está emprestando?</label>
          <input type="text" class="form-control" id="vista_chave" name="vista_chave" value="">
        </div>
        <input type="hidden" id="id_chave" name="id_chave" value="">      
        <input type="hidden" id="disponivel_chave" name="disponivel_chave" value="">      
        <button type="submit" href="CRUDE/emprestar_chave.php"class="btn btn-primary">Emprestar</button>
    </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    </div>
  </div>
</div>
</div>  

<!-- Modal DEVOLVER -->

<div class="modal fade" id="devolverModalLabel" tabindex="9999" role="dialog" aria-labelledby="devolverModalLabel" aria-hidden="true">

<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="devolverModalLabel">Devolver chave</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
    <form action="CRUDE/devolver_chave.php" method="POST">
            <div class="form-group">
          <label for="nome">Confirmar devolução?</label>
        <input type="hidden" id="id_chaveA" name="id_chave" value="">      
        <input type="hidden" id="disponivel_chaveA" name="disponivel_chave" value="">      
        <br><button type="submit" href="CRUDE/emprestar_chave.php"class="btn btn-primary">Confirmar</button></br>
            </div>
    </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    </div>
  </div>
</div>
</div> 
    <!-- Modal REGISTRAR -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a5a5afe9dc.js" crossorigin="anonymous"></script>

  <div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="registrationModalLabel">Registrar chave nova</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="CRUDE/cad_chave.php" method="POST">
            <div class="form-group">
              <label for="nome">Nome</label>
              <input type="text" class="form-control" id="nome_chave" name="nome_chave">
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal PERFILLLLLLL MAIS POLVORAAAA -->

  <div class="modal fade" id="perfilModal" tabindex="-1" role="dialog" aria-labelledby="perfilModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="perfilModalLabel">Meu perfil</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <?php

  // Configurações do banco de dados

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "EstoqueRenisson";

  // Cria uma conexão com o banco de dados
  $conn2 = new mysqli($servername, $username, $password, $dbname);

  // Verifica se a conexão foi estabelecida com sucesso
  if ($conn2->connect_error) {
      die("Falha na conexão com o banco de dados: " . $conn2->connect_error);
  }

  // Recupera o ID do usuário logado (substitua essa parte com a lógica de autenticação do seu sistema)

  // Consulta SQL para selecionar as informações do usuário logado
  $sqlPerfil = "SELECT id, login, email FROM usuarios WHERE id = $usuarioLogadoId";
  $resultPerfil = $conn2->query($sqlPerfil);

  // Verifica se foi encontrado um registro
  if ($resultPerfil->num_rows == 1) {
      // Recupera os dados do usuário logado
      $row = $resultPerfil->fetch_assoc();
      $id = $row["id"];
      $login = $row["login"];
      $email = $row["email"];

      // Exibe os dados no modal
    echo'  <div class="container">';
    echo'  <div class="row">';
    echo'    <div class="col-md-12">';
    echo'      <p><strong>ID:</strong> <span id="perfilID">';echo($id); echo'</span></p>';
    echo'      <p><strong>Nome:</strong> <span id="perfilNome">';echo($login); echo'</span></p>';
    echo'      <p><strong>Email:</strong> <span id="perfilEmail">';echo($email); echo'</span></p>';
    echo'    </div>';
    echo'  </div>';
    echo' </div>';
  } else {
      echo "Nenhum registro encontrado.";
  }

  ?>





        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>  

  <!-- Modal EDITAR -->

    <div class="modal fade" id="editarModalLabel" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
      
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarModalLabel">Editar produto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <form action="CRUDE/edit_prod.php" method="POST">
              
              <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="name_prod" name="name_prod" value="">
              </div>
              <div class="form-group">
                <label for="quantidade">Quantidade</label>
                <input type="text" class="form-control" id="qtd_prod" name="qtd_prod" value="">
              </div>
              <div class="form-group">
                <label for="valor">Local</label>
                <input type="text" class="form-control" id="valor_prod" name="valor_prod" value="">
              </div>
              <div class="form-group">
                <label for="categoria">Categoria</label>
                <input type="text" class="form-control" id="cat_prod" name="cat_prod" value="">
              </div>

              <input type="hidden" id="id_prod_edit" name="id_prod_edit" value="">      
              <button type="submit" class="btn btn-primary">Editar</button>
          </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

          </div>
        </div>
      </div>
    </div>  

  <!-- Modal EMPRESTIMO -->

  <div class="modal fade" id="emprestimoModalLabel" tabindex="-1" role="dialog" aria-labelledby="emprestimoModalLabel" aria-hidden="true">
      
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="emprestimoModalLabel">Empréstimo produto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <form action="CRUDE/emprestimo_prod.php" method="POST">
              
              <div class="form-group">
                <label for="quantidade">Quantidade a dar baixa</label>
                <input type="text" class="form-control" id="qtd_prod" name="qtd_prod" value="">
              </div>
              <div class="form-group">
                <label for="quantidade">Mutuário</label>
                <input type="text" class="form-control" id="mutuario_empr" name="mutuario_empr" value="">
              </div>
              <input type="hidden" id="id_prod_empr" name="id_prod_empr" value="">      
              <button type="submit" class="btn btn-primary">Confirmar empréstimo</button>
          </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

          </div>
        </div>
      </div>
    </div>  

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>


      $(document).ready(function() {
        $('.btn-add').click(function() {
          $('#registrationModal').modal('show');
        });
      });
    </script>

  <script>
  $(document).ready(function() {
    var thisprod_id;

    $('.btn-editar').click(function() {
      thisprod_id = $(this).data('thisproduto_id');
      $('#id_prod_edit').val(thisprod_id);

      $('#editarModalLabel').modal('show'); // Mostra o modal

    });

  });
  </script>

  <script>
  $(document).ready(function() {
    var thisprod_id;
      var thisdisponivel_chave;
    $('.btn-devolver_chave').click(function() {
      thisprod_id = $(this).data('thisproduto_id');
      thisdisponivel_chave = $(this).data('thisdisponivel_chave');
      $('#id_chaveA').val(thisprod_id);
      $('#disponivel_chaveA').val(thisdisponivel_chave);


      $('#devolverModalLabel').modal('show'); // Mostra o modal

    });

  });
  </script>
  <script>
  $(document).ready(function() {
    var thisprod_id;
      var thisdisponivel_chave;
    $('.btn-emprestar_chave').click(function() {
      thisprod_id = $(this).data('thisproduto_id');
      thisdisponivel_chave = $(this).data('thisdisponivel_chave');
      $('#id_chave').val(thisprod_id);
      $('#disponivel_chave').val(thisdisponivel_chave);


      $('#emprestarModalLabel').modal('show'); // Mostra o modal

    });

  });
  </script>
  <script>
  $(document).ready(function() {
    var thisprod_id;

    $('.btn-emprestimo').click(function() {
      thisprod_id = $(this).data('thisproduto_id');
      $('#id_prod_empr').val(thisprod_id);

      $('#emprestimoModalLabel').modal('show'); // Mostra o modal

    });

  });
  </script>

  <script>
      $(document).ready(function() {
        $('.nav-link').click(function(e) {
          e.preventDefault();

          $('.nav-link').removeClass('active');
          $(this).addClass('active');
          if ($(this).hasClass('baba')) {
            $('#perfilModal').modal('show');
          }
        });
      });
    </script>

  </script>
  <script>
    function confirmDelete() {
      return confirm("Tem certeza que deseja excluir esta chave?");
    }
  </script>
    </body>
  </html>