<?php
//Conexión mediante adodb5
include ("conexion.php");

//Consultas para llenar los list.
$Sql = "select * from cliente";
$Sql1 = "select * from banco";
$t = $db->Execute("select L.nombre, b.nombre, C.saldo from cuenta c join banco b
on c.idbanco = b.id join cliente l on c.idcliente = l.id");
$m = $t->getArray();
$nr = $t->recordCount();
$nc = $t->fieldCount();
$stmt = $db->Execute($Sql);
$stmtbb = $db->Execute($Sql1);
$rows = $stmt->getAll();
$rowsbb = $stmtbb->getAll();
$Mjs ="";

//CONSULTAR BANCOS
if(isset($_POST['BuscarB']))
{
    $cliente = $_POST['clientesE'];
    $cliente1 = $_POST['clientesR'];
    $SqlBancos = "select b.id, b.nombre from cuenta c join banco b
    on c.idbanco = b.id join cliente l on c.idcliente = l.id where l.id=".$cliente;
    $SqlBancos1 ="select b.id, b.nombre from cuenta c join banco b
    on c.idbanco = b.id join cliente l on c.idcliente = l.id where l.id=".$cliente1;
    $stmtb = $db->Execute($SqlBancos);
    $stmtb1 = $db->Execute($SqlBancos1);
    $rowsb = $stmtb->getAll();
    $rowsb1 = $stmtb1->getAll();
}

//INSERTAR CLIENTE.
if (isset($_POST['Acliente']))
{
	$id = $_POST['id'];
    $nombre = $_POST['nombre'];
    if(empty($id))
       {
          $Mjs="COMPLETAR CAMPOS.";
           echo '<script language="javascript">alert("'.$Mjs.'");</script>';
          
       }
    else
       {
	      $table = 'cliente';
	      $record = array();
	      $record["id"] = "$id";
	      $record["nombre"]  = "$nombre"; 
          $db->autoExecute($table,$record,'INSERT');
          $Mjs="INSERCIÒN REALIZADA.";
            echo '<script language="javascript">alert("'.$Mjs.'");</script>';
       }
}

//CREAR CUENTA CLIENTE.
if (isset($_POST['Ccuenta']))
{
	$idcliente = $_POST['ClientesRegistro'];
    $idbanco = $_POST['BancoRegistro'];
    $saldoc = $_POST['Saldo'];
    if(empty($idcliente))
       {
          $Msj="COMPLETAR CAMPOS.";
            echo '<script language="javascript">alert("'.$Mjs.'");</script>';
       }
    else
       {
	      $table = 'cuenta';
	      $record = array();
	      $record["idcliente"] = "$idcliente";
          $record["idbanco"]  = "$idbanco"; 
          $record["saldo"] = "$saldoc";
          $db->autoExecute($table,$record,'INSERT');
          $Mjs="INSERCIÒN REALIZADA.";
            echo '<script language="javascript">alert("'.$Mjs.'");</script>';
       }
}

//TRANSFERENCIAS.
if(isset($_POST['EnviarT']))
{
    $BancosE = $_POST['BancosE'];
    $BancosR = $_POST['BancosR'];
    $clientesE = $_POST['clientesE'];
    $clientesR = $_POST['clientesR'];
    $Cantidad = $_POST['Cantidad'];
  
    try {
        $db->BeginTrans();
        $Cons1 = $db->Execute("SELECT saldo FROM cuenta WHERE idcliente=$clientesE");
        $row = $Cons1->FetchRow();
        $Saldo = $row['saldo'];
        if($Saldo >= $Cantidad){
            
            $db->Execute("UPDATE cuenta set saldo=saldo-$Cantidad WHERE idcliente=$clientesE AND idbanco=$BancosE");
            $db->Execute("UPDATE cuenta set saldo=saldo+$Cantidad WHERE idcliente=$clientesR AND idbanco=$BancosR");
            $db->CommitTrans();
            $Mjs="TRANSACCIÒN REALIZADA.";
            echo '<script language="javascript">alert("'.$Mjs.'");</script>';
            
        } else{
            
            $db->RollbackTrans();
            $Mjs="TRANSACCIÒN CANCELADA POR FALTA DE FONDOS.";
            echo '<script language="javascript">alert("'.$Mjs.'");</script>';
        }

    } catch (Exception $ex) {
        echo "ERROR: ".$ex->getMessage();
        $db->RollbackTrans();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TRANSFERENCIAS BANCARIAS</title>

  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans|Candal|Alegreya+Sans">
  <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/imagehover.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
  <!--MENU-->
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html">TRANSFERENCIAS<span>BANCARIAS</span></a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#feature">Clientes</a></li>
          <li><a href="#organisations">Cuentas</a></li>
          <li><a href="#testimonial">Registrar cuenta</a></li>
          <li class="btn-trial"><a href="#contact">TRANSFERENCIAS</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="banner">
    <div class="bg-color">
      <div class="container">
        <div class="row">
          <div class="banner-text text-center">
            <div class="text-border">
              <h2 class="text-dec">Ràpidas & Seguras</h2>
            </div>
            <div class="intro-para text-center quote">
              <p class="big-text">TRANSFERENCIAS mediante Mysql & ADOdb</p>
              <p class="small-text">Diego Nuñez Medina<br>Angel Atonatl Tlachi<br>Ruben Michel Espindola Fuentes</p>
              <a href="#contact" class="btn get-quote">Realizar Transsacciòn</a>
            </div>
            <a href="#feature" class="mouse-hover">
              <div class="mouse"></div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Banner-->
  <!--Feature-->
  <section id="feature" class="section-padding">
    <div class="container">
      <div class="row">
        <div class="header-section text-center">
          <h2>Clientes</h2>
          <p>Trabajamos con los clientes de diferentes instituciones bancarias.</p>
          <hr class="bottom-line">
        </div>
        <div class="feature-info">
          <div class="fea">
            <div class="col-md-4">
              <div class="heading pull-right">
                <h4>Agregar Clientes</h4>
                <p>Registrate y elige el banco de tu</p>
              </div>
              <div class="fea-img pull-left">
                <i class="fa fa-css3"></i>
              </div>
              <div class="container">
                <div class="row">
                  <div class="header-section text-center">
                  </div>
                  <form name ="FormAgregarC" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" id=clientes>
                    <div class="col-md-6 col-sm-6 col-xs-12 left">
                      <div class="form-group">
                        <input type="text" name="id" class="form-control form" id="name" placeholder="Id" />
                        <div class="validation"></div>
                      </div>
                      <div class="form-group">
                        <input type="name" class="form-control" name="nombre" id="email" placeholder="Nombre"/>
                        <div class="validation"></div>
                      </div>
                      
                    </div>
                    <div class="col-xs-12">
                      <button type="submit" id="submit" name="Acliente" class="form contact-form-button light-form-button oswald light">Registrar</button>
                    </div>
                  </form>
          
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--/ feature-->
  <!--Organisations-->
  <section id="organisations" class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="orga-stru">
              <h3>65%</h3>
              <p>Bancomer</p>
              <i class="fa fa-male"></i>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="orga-stru">
              <h3>50%</h3>
              <p>Banamex</p>
              <i class="fa fa-male"></i>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="orga-stru">
              <h3>30%</h3>
              <p>Azteca</p>
              <i class="fa fa-male"></i>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-info">
            <hgroup>
              <h3 class="det-txt"> Trabajamos con las mejores instituciones bancarias.</h3>
              <h4 class="sm-txt">Cuentas bancarias</h4>
            </hgroup>
            <p class="det-p">
              <?PHP
            print "<table class='table table-striped table-dark'>";
            print "<thead>
            <tr>
            <th scope='col'>Cliente</th>
            <th scope='col'>Banco</th>
            <th scope='col'>Saldo</th>
            </tr>
            </thead>
            <tbody>";
for($r=0; $r<$nr; $r++)
{
    print"<tr>";
    for($c=0; $c<$nc; $c++)
    {
        print"<td>" . $m[$r][$c] . "</td>";

    }
    print"</tr>
    </tbody>";

    
}
print"</table>";
?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--/ Organisations-->
  
  
  <!--Testimonial-->
  <section id="testimonial" class="section-padding">
    <div class="container">
      <div class="row">
        <div class="header-section text-center">
          <h2 class="white">Cuentas</h2>
          <p class="white">Asociar clientes con sus cuentas.</p>
          <hr class="bottom-line bg-white">
          <div class="container">
            <div class="row">
              <div class="header-section text-center">
              </div>
              <form name ="Cuentas" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" id=cuentas>
              <div class="form-group">
              <select class="form-control form" name="ClientesRegistro">
              <option disabled hidden selected>Cliente</option>
       <?php foreach ($rows as $row)
       {
        if(isset($_POST["ClientesRegistro"]) && $_POST["ClientesRegistro"]==$row['id'])
           echo '<option value="'.$row['id'].'" selected>'.$row['nombre'].' </option>';
          else
           echo '<option value="'.$row['id'].'">'.$row['nombre'].' </option>';
          }?>
              </select>
            </div>
            <div class="form-group">
              <select class="form-control form" name="BancoRegistro">
              <option disabled hidden selected>Banco</option>
       <?php foreach ($rowsbb as $row)
       {
        if(isset($_POST["BancoRegistro"]) && $_POST["BancoRegistro"]==$row['id'])
           echo '<option value="'.$row['id'].'" selected>'.$row['nombre'].' </option>';
          else
           echo '<option value="'.$row['id'].'">'.$row['nombre'].' </option>';
          }?>
              </select>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="Saldo" id="Saldo" placeholder="Saldo" data-rule="minlen:4" data-msg="Introducir una cantidad valida." />
            </div>
          </div>
          <div class="col-xs-12">
            <button type="submit" id="Ccuenta" name="Ccuenta" class="form contact-form-button light-form-button oswald light">Registrar Cuenta</button>
          </div>
              </form>
      
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
        </div>
        <div class="container text-center">
          
        </div>
      </div>
    </div>
  </section>
  <!--/ Testimonial-->
 
  <!--Contact-->
  <section id="contact" class="section-padding">
    <div class="container">
      <div class="row">
        <div class="header-section text-center">
          <h2>TRANSFERENCIAS</h2>
          <hr class="bottom-line">
        </div>
        <form name ="Transaccion" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" id=combos>
          <div class="col-md-6 col-sm-6 col-xs-12 left">
            <div class="form-group">
              <select class="form-control form" name="clientesE">
              <option disabled hidden selected>Envìa</option>
        <?php foreach ($rows as $row)
        { 
        if(isset($_POST["clientesE"]) && $_POST["clientesE"]==$row['id'])
	       echo '<option value="'.$row['id'].'" selected>'.$row['nombre'].' </option>';
        else
	       echo '<option value="'.$row['id'].'">'.$row['nombre'].' </option>';
        } ?>
              </select>
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <select class="form-control form" name="clientesR">
              <option disabled hidden selected>Recibe</option>  
       <?php foreach ($rows as $row)
       {
	   if(isset($_POST["clientesR"]) && $_POST["clientesR"]==$row['id'])
	      echo '<option value="'.$row['id'].'" selected>'.$row['nombre'].' </option>';
       else
	      echo '<option value="'.$row['id'].'">'.$row['nombre'].' </option>';
       }?>
              </select>
              <div class="validation"></div>
            </div>
            <div class="col-xs-12">
              <button type="submit" id="submit" name="BuscarB" class="form contact-form-button light-form-button oswald light">BANCOS</button>
            </div>
            <div class="form-group">
              <select class="form-control form" name="BancosE">
              <option disabled hidden selected>Bancos Envìa</option>
       <?php foreach ($rowsb as $row)
       {
	   echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
       }?>
              </select>
            </div>
            <div class="form-group">
              <select class="form-control form" name="BancosR">
              <option disabled hidden selected>Bancos Recibe</option>
       <?php foreach ($rowsb1 as $row)
       {
	   echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
       }?>
              </select>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="Cantidad" id="Cantidad" placeholder="Cantidad" data-rule="minlen:4" data-msg="Introducir una cantidad valida." />
            </div>
          </div>
          <div class="col-xs-12">
            <button type="submit" id="submit" name="EnviarT" class="form contact-form-button light-form-button oswald light">REALIZAR TRANSACCIÒN</button>
          </div>
        </form>
      </div>
    </div>
  </section>
  <footer id="footer" class="footer">
    <div class="container text-center">
      <h3>¡Trabaja con nosotros!</h3>
      ©2020 Base de Datos para Aplicaciones 8E
    </div>
  </footer>
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery.easing.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/custom.js"></script>
  <script src="contactform/contactform.js"></script>

</body>

</html>
