<!DOCTYPE html>
<html lang="es">
  <?php 
    include 'templates/header.php';
  ?>
  <title>Criticos del dia - APTIV</title>
  <body class="nav-md-12">
    <div class="container body">
      <div class="main_container">
      

        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="loginModal">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">

              <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel2">Cargar volumenes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                          
                <p>Ingrese numero de empleado</p>
                <input type="text" class="form-control" id="numEmpleado">
                <p>Ingrese password</p>
                <input type="password"  class="form-control" id="passEmpleado">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnEnter">Ingresar</button>
              </div>

            </div>
          </div>
        </div>
        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="qtyUpdateModal">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">

              <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel2">Actualizar inventario</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                          
                <p>Leadcode</p>
                <input type="text" class="form-control" id="LeadcodeUpdated" readonly>
                <p>Cantidad</p>
                <input type="number"  class="form-control" min="1" value="1" id="qtyUpdated">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button id="btnEnterCount" class="btn btn-primary" >Actualizar</button>
              </div>
 
            </div>
          </div>
        </div>



                  <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="uploadInfoModal">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">

                        <div class="modal-header">
                          <h4 class="modal-title" id="myModalLabel2">Cargar volumenes</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                        </div> 
                        <div class="modal-body">
                        
                          
                          <button class="btn btn-primary" id="downloadTemplate"><i class="fa fa-download"></i> Descargar plantilla</button>
                          <form action="#" id="excel" accept=".xlsx" class="">
                                    <button class="btn btn-success" id="newUpload-info" type="file" accept='.xlsx'><i class="fa fa-upload"></i> Cargar informacion</button>
                                    <!--<button class="btn btn-primary" id="upload-info" type="file" accept='.xlsx'><i class="fa fa-upload"></i> Cargar informacion</button>-->

                                    <input id="file-upload" accept='.xlsx' type="file"/>
                                  </form>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="updateCountsModal">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">

                        <div class="modal-header">
                          <h4 class="modal-title" id="myModalLabel2">Cargar conteo de inventario</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                        
                          <div>
                            <span>Suba el archivo generado por la pagina web al descargar los criticos. Seleccione el archivo y de click en Cargar Informacion</span>
                            <br>
                            <form action="#" id="excel_" accept=".xlsx" class="">
                               <input id="file-uploadCount" accept='.xlsx' type="file"/>
                               <br>
                                      <button class="btn btn-success" id="newUploadCount-info" type="file" accept='.xlsx'><i class="fa fa-upload"></i> Cargar informacion</button>
                                      <!--<button class="btn btn-primary" id="upload-info" type="file" accept='.xlsx'><i class="fa fa-upload"></i> Cargar informacion</button>-->

                                     
                            </form>

                          </div>
                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                         
                        </div>

                      </div>
                    </div>
                  </div>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div>
                <div class="col-md-12">
                        
                   <img class="col-md-2" style="display: flex; margin-top: 0.5%; "  src="src/Aptiv_logo.png">
                  <div class="col-md-10">
                    <div class="col-md-1" style="margin-top: 1%;">
                      <img class="pull-right alert-icon blinking" style="width: 41%;" src="src/ambulance.png">
                    </div>
                    <h1 class="col-md-7" style="color: black; text-align: center;margin-left: 2.5%; font-weight: bold;" id="titleCriticos">Monitoreo de material critico</h1>
                    <div class="col-md-2" style="margin-top: 1%;">
                      <img class="pull-left alert-icon blinking" style="width: 18%;" src="src/ambulance.png">
                    </div>
                    <div class="col-md-2">
                      <span id="time" class="pull-right" style="font-size: x-large; display: flex; margin-top: 13%; color:black;"></span>
                    </div>
                  </div>
                      
                        
                </div>
              </div>
            </div>
                
                      
                <div class="col-md-3" style="text-align: center !important; "></div>
                <div class="col-md-6" style="text-align: center !important; ">

      
            </div>

            <div class="clearfix"></div>

            <div class="row" >
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
                        <button class="btn btn-primary pull-right" id="loginButton"><i class="fa fa-cog"></i></button>
                          <button class="btn btn-success pull-right" id="uploadDaily"><i class="fa fa-upload"></i> Subir informacion de conteo</button>
                         
                      </div>
                    </div>       
                       
                    </div>

                    <div style="text-align:center;">
                      <div class="botones-container" style="text-align: center;" >
                          <button   class="btn btn-primary text-light boton-margen boton-responsivo" onclick="$('#table_criticos').DataTable().button('.buttons-excel').trigger('click')"><i class="fa fa-print"></i> Imprimir contenido</button>
                          <button class="btn btn-info" onclick="exportToXLSX()"><i class="fa fa-print"> </i> Imprimir solo criticos</button>
                      </div>
                    </div>
                    <div id="column-visibility" class="form-group">
                        <label for="column-select">Mostrar columnas:</label>
                        <select id="column-select" class="form-control" multiple>
                            <option value="0">LeadCode</option>
                            <option value="1">Loc</option>
                            <option value="2">Maquina</option>
                            <option value="3">Color</option>
                            <option value="4">Board</option>
                            <option value="5">Estacion</option>
                            <option value="6">Turno</option>
                            <option value="7">VolumenDiario</option>
                            <option value="8">UsoHora</option>
                            <option value="9">InventarioContado</option>
                            <option value="10">HoraConteo</option>
                            <option value="11">InventarioActual</option>
                            <option value="12">HorasInventario</option>
                            <option value="13">Acciones</option>
                        </select>
                    </div>
                     <table id="table_criticos" style="text-align: center; font-size: 14px; align-content: center; width: 100%;" class="table table-sm table-striped table-bordered">
                        <thead>
                          <tr>
                              <th><input type="text" class="form-control" placeholder="Filtrar LeadCode" /></th>
                              <th><input type="text" class="form-control" placeholder="Filtrar Loc" /></th>
                              <th><input type="text" class="form-control" placeholder="Filtrar Maquina" /></th>
                              <th><input type="text" class="form-control" placeholder="Filtrar Color" /></th>
                              <th><input type="text" class="form-control" placeholder="Filtrar Board" /></th>
                              <th><input type="text" class="form-control" placeholder="Filtrar Estacion" /></th>
                              <th><input type="text" class="form-control" placeholder="Filtrar Turno" /></th>
                              <th><input type="text" class="form-control" placeholder="Filtrar VolumenDiario" /></th>
                              <th><input type="text" class="form-control" placeholder="Filtrar UsoHora" /></th>
                              <th><input type="text" class="form-control" placeholder="Filtrar InventarioContado" /></th>
                              <th><input type="text" class="form-control" placeholder="Filtrar HoraConteo" /></th>
                              <th><input type="text" class="form-control" placeholder="Filtrar InventarioActual" /></th>
                              <th><input type="text" class="form-control" placeholder="Filtrar HorasInventario" /></th>
                          </tr>
                          <tr>
                            
                            <th>LeadCode</th>
                            <th>Locacion</th>
                            <th>Maquina</th>
                            <th>Color</th>
                            <th>Board</th>
                            <th>Estacion</th>
                            <th>Turno</th>
                            <th>Volumen Diario</th>
                            <th>Uso x Hora</th>
                            <th>Inventario Contado</th>
                            <th>Hora de Conteo</th>
                            <th>Inventario Actual</th>
                            <th>Horas de Inventario</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>


                        <tbody style="font-size: 12px !important;" id="trTable">
                                        
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
        transform: scale(2.0);
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
<script src="build/js/criticosModel.js"></script>