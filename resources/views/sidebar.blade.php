<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{URL::to('/')}}" class="brand-link">
    <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text font-weight-light">Rutas</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <!-- <li class="nav-item has-treeview menu-open">
          <a href="#" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="./index.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Dashboard v1</p>
              </a>
            </li>
          </ul>
        </li> -->
        <!-- <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Distritos
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{URL::to('distritos-federales')}}" class="nav-link">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>
                  Federales
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{URL::to('distritos-locales')}}" class="nav-link">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>
                  Locales
                </p>
              </a>
            </li>
          </ul>
        </li> -->

        @if(isset($accesos_modulos))

          @if(sizeof($accesos_modulos))
            @foreach($accesos_modulos as $k => $row)

            <li class="nav-item">
              <a href="{{ URL::to($row->url) }}" class="nav-link">
                <i class="nav-icon {{ $row->icon ?? 'fas fa-calendar-alt'}}"></i>
                <p>
                  {{ $row->descripcion }}
                </p>
              </a>
            </li>
            @endforeach
          @else
            @if(Auth::user()->id == 1)
              <li class="nav-item">
                <a href="{{URL::to('usuarios')}}" class="nav-link">
                  <i class="nav-icon fas fa-calendar-alt"></i>
                  <p>
                    Usuarios
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{URL::to('modulos')}}" class="nav-link">
                  <i class="nav-icon fas fa-calendar-alt"></i>
                  <p>
                    Módulos
                  </p>
                </a>
              </li>
            @endif
          @endif
        @endif

        <!-- <li class="nav-header">SISTEMA</li>
        <li class="nav-item">
          <a href="{{URL::to('rutas')}}" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
              Rutas
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{URL::to('contactos')}}" class="nav-link">
            <i class="fas fa-users"></i>
            <p>
              Contactos
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{URL::to('coordinadores')}}" class="nav-link">
            <i class="fas fa-users"></i>
            <p>
              Coordinadores
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{URL::to('referentes')}}" class="nav-link">
            <i class="fas fa-users"></i>
            <p>
              Referentes
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{URL::to('casillas')}}" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
              Casillas
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{URL::to('secciones')}}" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
              Secciones
            </p>
          </a>
        </li> -->
        @if(Auth::user()->id == 1 || Auth::user()->id == 3)
        <!-- <li class="nav-item">
          <a href="{{URL::to('usuarios')}}" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
              Usuarios
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{URL::to('modulos')}}" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
              Módulos
            </p>
          </a>
        </li> -->
        @endif
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>