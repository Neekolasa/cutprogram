<!DOCTYPE html>
<html lang="es">
  <?php 
    include 'templates/header.php';
  ?>
  <title>Ruta interna de cable - APTIV</title>
  <body class="nav-md-12">
    <div class="container body">
      <div class="main_container">
      


        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div>
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
              </div>
                
                      
                <div class="col-md-3" style="text-align: center !important; "></div>
                <div class="col-md-6" style="text-align: center !important; ">

      
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">

                  <div class="x_title">
                 


                   
                        
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

                  <div class="x_content">
                    <div style="">
                      <div class="col-md-12" >
                        <div class="col-sm-3"></div>
                         <button class="btn btn-primary pull-right" onclick="window.location.replace('pedidos.php')"><i class="fa fa-external-link"></i></button>
                        <div class="col-sm-12">
                           <input id="num_empleado" type='text' class="form-control col-md-4 pull-left" placeholder="Numero de empleado" style="text-align: center; margin-right: 3%;" />
                           <input id="leadcode_number" type='text' class="form-control col-md-4 pull-right" placeholder="Ingrese LeadCode" style="text-align: center; margin-right: 3%;" />
                            <button id="leadcodeButton" class="btn btn-success col-md-2">Finalizar escaneo</button>
                        </div>
                    </div>       
                       
                    </div>

                    <div style="text-align:left;">
                      <div class="botones-container" hidden>
                          <button class="btn btn-primary text-light boton-margen boton-responsivo" onclick="$('#table_criticos').DataTable().button('.buttons-print').trigger('click')">Imprimir documento</button>
                      </div>
                    </div>
                     <table id="table_criticos" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            
                            <th>LeadCode</th>
                            <th>Color</th>
                            <th>Rack</th>
                            <th>Nivel</th>
                            <th>Ri</th>
                            <th>e</th>
                            <th>l</th>
                            <th>Piso</th>
                            <th>Loc</th>
                            <th>Board</th>
                                      
                                          
                          </tr>
                        </thead>


                        <tbody style="font-size: 12px !important; font-weight: bold;" id="trTable">
                                        
                        </tbody>
                    </table>
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
</style>
<script src="build/js/pedidosModel.js"></script>