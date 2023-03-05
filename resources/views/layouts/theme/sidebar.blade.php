<div class="sidebar-wrapper sidebar-theme">
    <nav id="compactSidebar">
        <ul class="menu-categories">
            {{--@role('admin')--}} <!--restringir acceso a nivel frontend al componente con middleware de roles-->
            <li class="active">
                <a href="{{ url('categories') }}" class="menu-toggle" data-active="true">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                        </div>
                        <span>CATEGORIAS</span>
                    </div>
                </a>
            </li>
            {{--@endcan--}}
            {{--@role('admin')--}} <!--restringir acceso a nivel frontend al componente con middleware de roles-->
            <li class="">
                <a href="{{ url('subcategories') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server">
                                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                                <line x1="6" y1="6" x2="6.01" y2="6"></line>
                                <line x1="6" y1="18" x2="6.01" y2="18"></line>
                            </svg>
                        </div>
                        <span>SUBCATEGORIAS</span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="{{ url('presentations') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-stop-circle">
                                <circle cx="12" cy="12" r="10"></circle>
                                <rect x="9" y="9" width="6" height="6"></rect>
                            </svg>
                        </div>
                        <span>PRESENTACIONES</span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="{{ url('brands') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-stop-circle">
                                <circle cx="12" cy="12" r="10"></circle>
                                <rect x="9" y="9" width="6" height="6"></rect>
                            </svg>
                        </div>
                        <span>MARCAS</span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="{{ url('containers') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-stop-circle">
                                <circle cx="12" cy="12" r="10"></circle>
                                <rect x="9" y="9" width="6" height="6"></rect>
                            </svg>
                        </div>
                        <span>CONTENEDORES</span>
                    </div>
                </a>
            </li>
            {{--@endcan--}}
            {{--@role('admin')--}} <!--restringir acceso a nivel frontend al componente con middleware de roles-->
            <li class="">
                <a href="{{ url('offices') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <span>SUCURSALES</span>
                    </div>
                </a>
            </li>
            {{--@endcan--}}
            {{--@can('Product_Index')--}} <!--restringir acceso a nivel frontend al componente a traves de los permisos-->
            <li class="">
                <a href="{{ url('products') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                        </div>
                        <span>PRODUCTOS</span>
                    </div>
                </a>
            </li>
            {{--@endcan--}}
            {{--@can('Stock_Index') <!--restringir acceso a nivel frontend al componente a traves de los permisos-->
            <li class="">
                <a href="{{ url('stocks') }}" class="menu-toggle" data-active="false">
            <div class="base-menu">
                <div class="base-icons">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trello">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <rect x="7" y="7" width="3" height="9"></rect>
                        <rect x="14" y="7" width="3" height="5"></rect>
                    </svg>
                </div>
                <span>STOCK</span>
            </div>
            </a>
            </li>
            @endcan
            @can('Ventas_Index') <!--restringir acceso a nivel frontend al componente a traves de los permisos-->
            <li class="">
                <a href="{{ url('pos') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" heigth="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </div>
                        <span>VENTAS</span>
                    </div>
                </a>
            </li>
            @endcan
            @role('admin') <!--restringir acceso a nivel frontend al componente con middleware de roles-->
            <li class="">
                <a href="{{ url('roles') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" heigth="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key">
                                <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4">
                                </path>
                            </svg>
                        </div>
                        <span>ROLES</span>
                    </div>
                </a>
            </li>
            @endcan
            @role('admin') <!--restringir acceso a nivel frontend al componente con middleware de roles-->
            <li class="">
                <a href="{{ url('permisos') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" heigth="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                        </div>
                        <span>PERMISOS</span>
                    </div>
                </a>
            </li>
            @endcan
            @role('admin') <!--restringir acceso a nivel frontend al componente con middleware de roles-->
            <li class="">
                <a href="{{ url('asignar') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" heigth="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </div>
                        <span>ASIGNAR</span>
                    </div>
                </a>
            </li>
            @endcan
            @role('admin') <!--restringir acceso a nivel frontend al componente con middleware de roles-->
            <li class="">
                <a href="{{ url('users') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" heigth="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <span>USUARIOS</span>
                    </div>
                </a>
            </li>
            @endcan
            {{--@can('Denomination_Index')--}} <!--restringir acceso a nivel frontend al componente a traves de los permisos-->
            <li class="">
                <a href="{{ url('coins') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-stop-circle">
                                <circle cx="12" cy="12" r="10"></circle>
                                <rect x="9" y="9" width="6" height="6"></rect>
                            </svg>
                        </div>
                        <span>MONEDAS</span>
                    </div>
                </a>
            </li>
            <li class="">
                <a href="{{ url('statuses') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-stop-circle">
                                <circle cx="12" cy="12" r="10"></circle>
                                <rect x="9" y="9" width="6" height="6"></rect>
                            </svg>
                        </div>
                        <span>ESTADOS</span>
                    </div>
                </a>
            </li>
            {{--@endcan--}}
            {{--@can('Ingresos_Index') <!--restringir acceso a nivel frontend al componente a traves de los permisos-->
            <li class="">
                <a href="{{ url('incomes') }}" class="menu-toggle" data-active="false">
            <div class="base-menu">
                <div class="base-icons">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-in">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                </div>
                <span>INGRESOS</span>
            </div>
            </a>
            </li>
            @endcan
            @can('Egresos_Index') <!--restringir acceso a nivel frontend al componente a traves de los permisos-->
            <li class="">
                <a href="{{ url('cashout') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" heigth="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <span>EGRESOS</span>
                    </div>
                </a>
            </li>
            @endcan
            @can('Traspasos_Index') <!--restringir acceso a nivel frontend al componente a traves de los permisos-->
            <li class="">
                <a href="{{ url('transfers') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                        </div>
                        <span>TRASPASOS</span>
                    </div>
                </a>
            </li>
            @endcan
            @can('Reportes_Index') <!--restringir acceso a nivel frontend al componente a traves de los permisos-->
            <li class="">
                <a href="{{ url('reports') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" heigth="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart">
                                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                            </svg>
                        </div>
                        <span>REPORTES</span>
                    </div>
                </a>
            </li>
            @endcan--}}
        </ul>
    </nav>
</div>