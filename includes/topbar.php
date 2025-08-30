
        <!-- Begin page -->
        <div id="wrapper">
            <!-- INICIO - Navigation Bar-->
            <header id="topnav">
                <!-- INICIO - topbar -->
                <div class="topbar-main">
                    <div class="container">
                        <!-- INICIO - Logo -->
                        <div class="topbar-right">
                            <div class="text-center">
                                <a href="../dashboard/dashboard.php" class="logo"><span><img src="../assets/images/logo_coris_peq_coris.png" alt="CORIS"></span> </a>
                            </div>
                        </div>
                        <!-- FIN - Logo -->

                        <div class="menu-extras">
                            <!-- INICIO - Datos usuario + Logout -->
                            <ul class="nav navbar-nav navbar-right pull-right">
                                <li class="dropdown"> 
                                    <a href="">
                                        <i class="ti-user m-r-5"></i> <?php print_r($sesion_usuario_nombre . ' ' . $sesion_usuario_apellido . ' | ' . $sesion_usuario_rol); ?>
                                    </a>
                                </li>
                            </ul>
                            <!-- FIN - Datos usuario + Logout -->

                            <div class="menu-item">
                                <!-- Mobile menu toggle-->
                                <a class="navbar-toggle">
                                    <div class="lines">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                                <!-- End mobile menu toggle-->
                            </div>
                        </div>

                    </div>
                </div>
                <!-- FIN - topbar -->

                <!-- INICIO - Navbar -->
                <div class="navbar-custom">
                    <div class="container">
                        <div id="navigation">
                            <!-- INICIO - Menu-->
                            <ul class="navigation-menu">
                                <!-- INICIO - Dashboard -->
                                <?php
                                    if (Usuario::puede("dashboard_ver") == 1) {
                                ?>
                                    <li class="has-submenu">
                                        <a href="../dashboard/dashboard.php"><i class="md md-dashboard"></i>Dashboard</a>
                                    </li>
                                <?php } ?>    
                                <!-- FIN - Dashboard -->
                                
                                <!-- INICIO - Casos -->
                                <?php
                                    if (Usuario::puede("casos_ver_menu") == 1) {
                                ?>
                                    <li class="has-submenu">
                                        <a href="javascript:void(0);"><i class="md md-public"></i>Casos</a>
                                        <ul class="submenu">
                                            <?php
                                                if (Usuario::puede("casos_alta") == 1) { 
                                            ?>
                                                <li><a id="nuevoCaso" href="../caso/caso.php?ncase=1">Nuevo Casos</a></li>
                                            <?php } ?>
                                            <?php
                                                if (Usuario::puede("casos_ver") == 1) { 
                                            ?>    
                                                <li><a id="verCasos" href="../caso/caso.php">Ver Casos</a></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <!-- FIN - Casos -->
                                
                                <!-- INICIO - Agenda -->
                                <?php
                                    if (Usuario::puede("agenda_ver") == 1) {                                                            
                                ?>
                                    <li class="has-submenu">
                                        <a href="javascript:void(0);"><i class="md md-today"></i>Agenda</a>
                                        <ul class="submenu">
                                            <li><a href="../agenda/agenda.php">Agenda Casos</a></li>
                                            <li><a href="../agenda/agenda_reintegros.php">Agenda Reintegros</a></li>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <!-- FIN - Agenda -->
                                
                                <!-- INICIO - Facturas -->
                                <?php
                                    if (Usuario::puede("facturas_ver") == 1) {
                                ?>
                                    <li class="has-submenu">
                                        <a href="javascript:void(0);"><i class="md md-assignment"></i>Facturación</a>
                                        <ul class="submenu">
                                            <li><a href="../facturacion/facturacion.php">Facturas</a></li>
                                            <li><a href="../facturacion_old/facturacion_old.php">Facturas (SGC1)</a></li>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <!-- FIN - Facturas -->

                                <!-- INICIO - Procesos -->
                                <?php
                                    if (Usuario::puede("ver_menu_procesos") == 1) {
                                ?>
                                <li class="has-submenu">
                                    <a href="javascript:void(0);"><i class="md md-cached"></i>Procesos</a>
                                    <ul class="submenu">
                                        <li class="has-submenu">
                                            <a href="#">Reintegros</a>
                                            <ul class="submenu">
                                                <?php if (Usuario::puede("reintegros_generar_ordenPago") == 1) { ?>
                                                    <li><a href="../reporte/reporte_reintegros_ordenPago.php">Generar Orden de Pago</a></li>
                                                <?php } ?>
                                                <?php if (Usuario::puede("reintegros_pago") == 1) { ?>
                                                    <li><a href="../reporte/reporte_reintegros_pago.php">Pago de Reintegros</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <li class="has-submenu">
                                            <a href="#">Facturas</a>
                                            <ul class="submenu">
                                                <?php if (Usuario::puede("fci_generar_ordenAuditoria") == 1) { ?>
                                                    <li><a href="../reporte/reporte_fci_ordenAuditoria.php">Generar Orden de Auditoria</a></li>
                                                <?php } ?>
                                                <?php if (Usuario::puede("fci_generar_ordenAuditoria1") == 1) { ?>
                                                    <li><a id="" href="../facturacion/fci_orden_pago.php">Facturas Orden de Pago</a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <?php if (Usuario::puede("generar_accrued") == 1) { ?>
                                            <li><a id="" href="../accrued/accrued.php">Crear Accrued</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php } ?>
                                <!-- FIN - Procesos -->

                                <!-- INICIO - Reportes -->
                                <?php
                                    if (Usuario::puede("reportes_ver_menu") == 1) {
                                ?>
                                <li class="has-submenu">
                                    <a href="javascript:void(0);"><i class="md md-assessment"></i>Reportes</a>
                                    <ul class="submenu">
                                        <li class="has-submenu">
                                            <a href="#">Casos</a>
                                            <ul class="submenu">
                                                <li><a href="../reporte/reporte_casosAvanzados.php">Avanzado de Casos</a></li>       
                                                <li><a href="../reporte/reporte_gopEnviadas.php">Gop enviadas</a></li>
                                                <li><a href="../reporte/reporte_estadisticaOperador.php">Estadisticas por Operador</a></li>     
                                                <li><a href="../reporte/reporte_datosContacto.php">Datos de Contacto</a></li>
                                            </ul>
                                        </li>
                                        <li class="has-submenu">
                                            <a href="#">Reintegros</a>
                                            <ul class="submenu">
                                                <li><a href="../reporte/reporte_reintegros_general.php">General de Reintegros</a></li>
                                                <li><a href="../reporte/reporte_reintegros_modelo1.php">Reintegros Pendientes Pago</a></li>
                                                <li><a href="../reporte/reporte_reintegros_op300.php">Reintegros mayores a USD300</a></li>
                                            </ul>
                                        </li>
                                        <li class="has-submenu">
                                            <a href="#">Facturación</a>
                                            <ul class="submenu">
                                                <li><a href="../reporte/reporte_facturacion.php">General de Facturación</a></li>
                                                <li><a href="../reporte/reporte_fci_ingresado.php">Facturas con Items ingresados</a></li>
                                            </ul>
                                        </li>
                                        <li class="has-submenu">
                                            <a href="#">Accrued</a>
                                            <ul class="submenu">
                                                <li><a href="../accrued/accrued_generados.php">Accrued Generados</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>
                                <!-- FIN - Reportes -->
                                
                                <!-- INICIO - ABM Tablas -->
                                <?php
                                    if (Usuario::puede("tablas_ver_menu") == 1) {
                                ?>
                                <li class="has-submenu">
                                    <a href="javascript:void(0);"><i class="md md-view-list"></i>ABM Tablas</a>                                
                                    <ul class="submenu">
                                        <?php
                                            if (Usuario::puede("prestadores_ver") == 1) { 
                                        ?>
                                            <li><a href="../prestador/prestador.php">Prestadores</a></li>
                                        <?php } ?> 
                                        <?php
                                            if (Usuario::puede("clientes_ver") == 1) {  
                                        ?>
                                            <li><a href="../cliente/cliente.php">Clientes</a></li>
                                        <?php } ?>
                                        <?php
                                            if (Usuario::puede("ciudades_ver") == 1) {  
                                        ?>    
                                            <li><a href="../ciudad/ciudad.php">Ciudades</a></li>
                                        <?php } ?>
                                        <?php
                                            if (Usuario::puede("diagnosticos_ver") == 1) {  
                                        ?>    
                                            <li><a href="../diagnostico/diagnostico.php">Diagnósticos</a></li>
                                        <?php } ?>
                                        <?php
                                            if (Usuario::puede("practicas_ver") == 1) {  
                                        ?>                                            
                                            <li><a href="../practica/practica.php">Practicas</a></li>
                                        <?php } ?>
                                        <?php
                                            if (Usuario::puede("tipoAsistencia_ver") == 1) {  
                                        ?>                                            
                                            <li><a href="../tipoAsistencia/tipoAsistencia.php">Tipos de Asistencia</a></li>
                                        <?php } ?>
                                        <?php
                                            if (Usuario::puede("tipoPrestadores_ver") == 1) {  
                                        ?>
                                            <li><a href="../tipoPrestador/tipoPrestador.php">Tipos de Prestadores</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php } ?>
                                <!-- FIN - ABM Tablas -->
                                
                                <!-- INICIO - Accesos -->
                                <?php
                                    if (Usuario::puede("accesos_ver_menu") == 1) {
                                ?>
                                <li class="has-submenu">
                                    <a href="javascript:void(0);"><i class="md md-verified-user"></i>Accesos</a>                                  
                                    <ul class="submenu">
                                        <li><a href="../usuario/usuario.php">Usuarios</a></li>
                                        <?php
                                            if ($sesion_usuario_id == 70) { 
                                        ?>
                                            <li><a href="../rol/rol.php">Roles</a></li>
                                            <li><a href="../permiso/permiso.php">Permisos</a></li>
                                        <?php } ?>  
                                    </ul>
                                </li>
                                <?php } ?>                                
                                <!-- FIN - Accesos -->
            
                                <!-- INICIO - Logout -->  
                                <li class="has-submenu pull-right">
                                    <a href="../seguridad/logout.php"><i class="md md-https"></i>Salir</a>
                                </li>
                                <!-- FIN - Logout -->   
                            </ul>
                            <!-- FIN - Menu-->
                        </div>
                    </div>
                </div>
                <!-- FIN - Navbar -->
            </header>
            <!-- FIN - Navigation Bar-->
            
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->                      
            <div class="wrapper">
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">