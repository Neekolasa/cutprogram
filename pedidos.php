<!DOCTYPE html>
<html lang="es">
  <?php 
    include 'templates/header.php';
  ?>
  <title>Pedidos de leadcodes - APTIV</title>
  <body class="nav-md-12">
    <div class="container body">
      <div class="main_container">
      
        <div class="top_nav" style="margin-left: auto; !important">
            <div class="nav_menu " >
              
                <nav class="nav navbar-nav">

                  <ul class=" navbar-right">

                    <li class="nav-item dropdown open" style="padding-left: 15px;">

                      <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                      
                        <img src="src/images/download.jpg" alt=""><span id="username_logged"></span>

                      </a>
                      <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item"  href="#" onclick="closeSession()"><i class="fa fa-sign-out pull-right"></i> Cerrar sesion</a>
                      </div>
                    </li>
    
                    
                  </ul>
                </nav>
              
               
            </div>
          </div>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
             
                <div class="col-md-12">
                        
                  <img class="col-md-2" style="display: flex; margin-top: 0.5%; "  src="src/Aptiv_logo.png">
                  <div class="col-md-10">
                    <div class="col-md-1" style="margin-top: 1%;">
                      <img class="pull-right alert-icon blinking" style="width: 41%;" src="src/car.png">
                    </div>
                    <h1 class="col-md-7" style="color: black; text-align: center;margin-left: 2.5%; font-weight: bold;" id="titleCriticos">Surtido de LeadCodes</h1>
                    <div class="col-md-2" style="margin-top: 1%;">
                      <img class="pull-left alert-icon blinking" style="width: 18%;" src="src/car.png">
                    </div>
                    <div class="col-md-2">
                      <span id="time" class="pull-right" style="font-size: x-large; display: flex; margin-top: 13%; color:black;"></span>
                    </div>
                  </div>
                      
                        
                </div>
                <div class="col-md-12" style=" font-size: 21px;color: black; text-align: center;">Desarrollado por: Ing Joel Andrade Enriquez</div>
                      
                <div class="col-md-3" style="text-align: center !important; "></div>
                <div class="col-md-6" style="text-align: center !important; ">

      
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">

                  <div class="x_title">
                  <h3>Lista de pedidos pendientes</h3>


                   
                        
                    </div>
                   
                    

                    <!--*************ADD CONTENT HERE*****************-->


                    <div class="clearfix"></div>
                  </div>
                  <style>
                    @media print {
                        body {
                            margin: 0;
                        }
                        table.dataTable {
                            width: 100% !important;
                            margin: 0;
                        }
                        table.dataTable th, table.dataTable td {
                            white-space: nowrap;
                            padding: 4px 8px;
                        }
                        @page {
                            margin: 0;
                        }
                        .no-print, .no-print * {
                            display: none !important;
                        }
                    }
                </style>
                  <div class="modal fade bs-example-modal-sm" id="modalPrint" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">

                        <div class="modal-header">
                          <h4 class="modal-title" id="myModalLabel">Reimprimir tarjeta viajera</h4>
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
           
                          <input type="text" id="leadCode" placeholder="Ingrese Leadcode" class="form-control">
                          <br><br>
                          <h5>Seleccione la linea</h5>
                          <div class="form-check">
                            <input class="form-check-input radios" type="radio" name="radioLinea" id="radioGM" value="GM" checked>
                            <label class="form-check-label" for="radioGM">
                              Linea GM
                            </label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input radios" type="radio" name="radioLinea" id="radioHonda" value="Honda">
                            <label class="form-check-label" for="radioHonda">
                              Linea Honda
                            </label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input radios" type="radio" name="radioLinea" id="radioStellantis" value="Stellantis">
                            <label class="form-check-label" for="radioStellantis">
                              Linea Stellantis
                            </label>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <button type="button" class="btn btn-primary" id="printCardAction"><i class="fa fa-print"></i> Imprimir</button>
                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="modal fade bs-example-modal-sm" id="modal_login" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                          <div class="modal-content">

                            <div class="modal-header">
                              <h4 class="modal-title" id="myModalLabel2">Validar acceso</h4>
                              <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                              </button>-->
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <form autocomplete="off">
                                <label for="badge">Numero de empleado <span style="color: red;">*</span></label>
                                <input type="text" min="0" name="badge" id="badge" class="form-control" required>
                                <br>
                                <br>
                                <label><span style="color: red;">*</span> Campos obligatorios</label>

                              </div>
                              
                            </div>
                            <div class="modal-footer">
                             
                              <button type="submit" id="ingresar_button" class="btn btn-primary">Ingresar</button>
                            </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal fade bs-example-modal-sm" id="exitTicketModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">

                        <div class="modal-header">
                          <h4 class="modal-title" id="myModalLabel2">Salida de ticket</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <input type="text" class="form-control" placeholder="Numero de folio" id="ticketNumber">
                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <button type="button" class="btn btn-primary" id="sendTicketExit">Aceptar</button>
                        </div>

                      </div>
                    </div>
                  </div>
                   <div class="modal fade bs-example-modal-sm" id="responsableModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">

                        <div class="modal-header">
                          <h4 class="modal-title" id="myModalLabel2">Asignar responsable</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <input type="text" class="form-control" placeholder="Numero de empleado" id="responsableBadge">
                          <input type="hidden" id="folioNumber">
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <button type="button" class="btn btn-primary" id="sendResponsable">Aceptar</button>
                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="x_content">
                    <div style="">
                      <div class="col-md-12" >

                        <div class="col-md-5" style="text-align: left;">
                          <button class="btn btn-success pull-left" id="exitTicketButton"><i class="fa fa-shopping-cart"></i> Dar salida a ticket</button>
                        </div>
                        <div class="col-md-2" style="text-align: center;">
                          <button class="btn btn-light" id="printCardButton"><i class="fa fa-print"></i> Reimprimir tarjeta</button>
                        </div>
                        <div class="col-md-5" style="text-align:right;">
                          <button class="btn btn-primary pull-right" onclick="window.location.replace('index.php')"><i class="fa fa-external-link"></i> Volver al inicio</button>
                        </div>
                         
                        
                    </div>       
                       
                    </div>

                    <div style="text-align:left;">
                      <div class="botones-container" hidden>
                          <button class="btn btn-primary text-light boton-margen boton-responsivo" onclick="$('#tablePedidos').DataTable().button('.buttons-print').trigger('click')">Imprimir documento</button>
                      </div>
                    </div>
                    <header></header>
                    <div style="">
                     <table id="tablePedidos" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            
                            <th>ID Ticket</th>
                            <th>Folio Ticket</th>
                            <th>Fecha de pedido</th>
                            <th>No Emp Pedido</th>
                            <th>Fecha de atendido</th>
                            <th>No Emp Atendido</th>
                            <th>Surtidor</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                                      
                                          
                          </tr>
                        </thead>


                        <tbody style="font-size: 12px !important; font-weight: bold;" id="trTable" class="mayus">
                                        
                        </tbody>
                    </table>
                    </div>
                    <br><br><br>
                    <div class="x_title">
                     <h3>Lista de pedidos completados</h3>


                   
                        
                    </div>
                    <div style="">
                     <table id="tableCompletados" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            
                            <th>ID Ticket</th>
                            <th>Folio Ticket</th>
                            <th>Fecha de pedido</th>
                            <th>No Emp Pedido</th>
                            <th>Fecha de atendido</th>
                            <th>No Emp Atendido</th>
                            <th>Surtidor</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                                      
                                          
                          </tr>
                        </thead>


                        <tbody style="font-size: 12px !important; font-weight: bold;" id="trTable" class="mayus">
                                        
                        </tbody>
                    </table>
                    </div>

                  </div>

                </div>
              </div>
            </div>



          </div>
        </div>

        <!-- /page content -->

        <!-- footer content -->
        <footer style="margin-left:auto;">
            <div class="pull-right">
                APTIV - Materials Admin Tool 
            </div>
            <div class="pull-left"> 
                Desarrollado por: Ing Joel Andrade Enriquez  
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <?php include 'templates/footerLibs.php' ?>  
  </body>
</html>


<style type="text/css">
  .dt-center{
    text-align: center;
  }
  .blur {filter: blur(5px);}
  .legend { list-style: none; }
  .legend li { float: left; margin-right: 10px; }
  .legend span { border: 1px solid #ccc; float: left; width: 12px; height: 12px; margin: 2px; }
  /* your colors */
  .legend .sinLlegada { background-color: #CA23B5; }
  .legend .sinLiberar { background-color: #F6384A; }
  .legend .ListoAlmacena { background-color: #F59533; }
  .legend .MaterialPU { background-color: #FAF667; }
  .legend .Surtido { background-color: #35E231; }
  @keyframes blink {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(3.1);
        opacity: 1;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.blinking {
    animation: blink 1s infinite;
    color: #ff5722; /* Cambiar el color */
    text-shadow: 0 0 5px #ff5722; /* Agregar sombra */
}
.mayus {
  text-transform: capitalize;
}
</style>
<script src="build/js/pedidosListaModel.js"></script>
<script src="build/js/JsBarcode.all.min.js"></script>
<script src="build/js/qrcode.min.js"></script>