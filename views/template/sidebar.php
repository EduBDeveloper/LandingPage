  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand Logo -->
    <a href="<?php echo $functions->direct_paginas()."home" ?>" class="brand-link">
      <img src="<?php echo $functions->direct_sistema(); ?>/img/chemistry.png" alt="" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-dark text-cyan"><strong>EduB</strong>Web </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- SidebarSearch Form -->
      <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search" data-not-found-text="No se encontraron resultados">
          <input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>
      
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a id="m_inicio" href="<?php echo $functions->direct_paginas()."home" ?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Inicio</p>
            </a>
          </li>

          <li class="nav-item">
            <a id="m_clientes" href="<?php echo $functions->direct_paginas()."clientes/registro-cliente" ?>" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>Clientes</p>
            </a>
          </li>

          <li class="nav-item has-treeview menu-close">
            <a id="m_almacen" href="#" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>
                Almacén
                <i class="right fas fa-angle-left nav-icon"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a id="m_registro_producto" href="<?php echo $functions->direct_paginas()."productos/registro-producto" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-danger"></i>
                  <p>Registro de Producto</p>
                </a>
              </li>
              <li class="nav-item">
                <a id="m_listado_producto" href="<?php echo $functions->direct_paginas()."productos/listado-producto" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Listado de Productos</p>
                </a>
              </li>
              <li class="nav-item">
                <a id="m_actualizar_stock" href="<?php echo $functions->direct_paginas()."productos/actualizar-stock" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-warning"></i>
                  <p>Actualizar Stock</p>
                </a>
              </li>
              <li class="nav-item">
                <a id="m_historial_movimiento" href="<?php echo $functions->direct_paginas()."productos/historial-movimiento" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Historial de Movimientos</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a id="m_depositos" href="<?php echo $functions->direct_paginas()."deposito/registro-deposito" ?>" class="nav-link">
              <i class="nav-icon fas fa-people-carry"></i>
              <p>Depositos</p>
            </a>
          </li>
          <li class="nav-item">
            <a id="m_proveedores" href="<?php echo $functions->direct_paginas()."proveedores/registro-proveedor" ?>" class="nav-link">
              <i class="nav-icon fas fa-people-carry"></i>
              <p>Proveedores</p>
            </a>
          </li>

          <li class="nav-header">Compras</li>

          <li class="nav-item has-treeview menu-close">
            <a id="m_cotizacion" href="#" class="nav-link">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>
                Cotización
                <i class="right fas fa-angle-left nav-icon"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a id="m_registro_cotizacion" href="<?php echo $functions->direct_paginas()."cotizacion/registro-cotizacion" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-danger"></i>
                  <p>Registro de Cotización</p>
                </a>
              </li>
              <li class="nav-item">
                <a id="m_resumen_cotizacion" href="<?php echo $functions->direct_paginas()."cotizacion/resumen-cotizaciones" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Resumen Cotizaciones</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview menu-close">
            <a id="m_facturacion" href="#" class="nav-link">
            <i class="nav-icon fas fa-file-invoice"></i>
              <p>
                Factura
                <i class="right fas fa-angle-left nav-icon"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a id="m_registro_factura" href="<?php echo $functions->direct_paginas()."facturacion/registro-factura" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-danger"></i>
                  <p>Registro de Factura</p>
                </a>
              </li>
              <li class="nav-item">
                <a id="m_resumen_factura" href="<?php echo $functions->direct_paginas()."facturacion/resumen-facturas" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Resumen de Facturas</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview menu-close">
            <a id="m_nota_credito" href="#" class="nav-link">
            <i class="nav-icon fas fa-file-invoice"></i>
              <p>
                Nota de Crédito
                <i class="right fas fa-angle-left nav-icon"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a id="m_registro_nota_credito" href="<?php echo $functions->direct_paginas()."facturacion/registro-nota-credito" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-danger"></i>
                  <p>Registro de NC</p>
                </a>
              </li>
              <li class="nav-item">
                <a id="m_registro_nota_debito" href="<?php echo $functions->direct_paginas()."facturacion/registro-nota-debito" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-danger"></i>
                  <p>Registro de ND</p>
                </a>
              </li>
              <li class="nav-item">
                <a id="m_resumen_nota_credito" href="<?php echo $functions->direct_paginas()."facturacion/resumen-notas-credito" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Resumen de NC</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-header">Ordenes / Presupuestos</li>

          <li class="nav-item has-treeview menu-close">
            <a id="m_ordenes" href="#" class="nav-link">
              <i class="nav-icon fas fa-receipt"></i>
              <p>
                Órdenes
                <i class="right fas fa-angle-left nav-icon"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a id="m_pedidos" href="<?php echo $functions->direct_paginas()."ordenes/pedidos" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-danger"></i>
                  <p>Pedidos</p>
                </a>
              </li>
              <li class="nav-item">
                <a id="m_presupuesto" href="<?php echo $functions->direct_paginas()."ordenes/presupuesto" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-danger"></i>
                  <p>Presupuesto</p>
                </a>
              </li>
              <li class="nav-item">
                <a id="m_orden_compra" href="<?php echo $functions->direct_paginas()."ordenes/orden-de-compra" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-danger"></i>
                  <p>Orden de Compra</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a id="m_resumen_orden" href="<?php echo $functions->direct_paginas()."ordenes/resumen-ordenes" ?>" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>Resumen de Órdenes</p>
                </a>
              </li>
            </ul>
          </li>

        

          <li class="nav-header"><i class="nav-icon fas fa-layer-group"></i> SISTEMA</li>

          <li class="nav-item has-treeview menu-close">
            <a id="m_reportes" href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Reportes
                <i class="right fas fa-angle-left nav-icon"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a id="m_rpt_clientes" href="<?php echo $functions->direct_paginas()."reportes/clientes" ?>" class="nav-link">
                <i class="fas fa-file-alt nav-icon"></i>
                <p>Clientes</p>
              </a>
              </li>
              <li class="nav-item">
                <a id="m_rpt_productos" href="<?php echo $functions->direct_paginas()."reportes/productos" ?>" class="nav-link">
                  <i class="fas fa-file-alt nav-icon"></i>
                  <p>Productos</p>
                </a>
              </li>
              
            
            </ul>
          </li>

          <li class="nav-item">
            <a id="m_empleados" href="<?php echo $functions->direct_paginas()."empleados/registro-empleado" ?>" class="nav-link">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>Empleados</p>
            </a>
          </li>

          <li class="nav-item">
            <a id="m_usuarios" href="<?php echo $functions->direct_paginas()."usuarios/registro-usuario" ?>" class="nav-link">
              <i class="nav-icon fas fa-user-cog"></i>
              <p>Usuarios</p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>