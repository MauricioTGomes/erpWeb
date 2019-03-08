<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="token" content="{{ csrf_token() }}">
    <title>Nome do | Software</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('/layout/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('/layout/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/layout/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/layout/dist/css/AdminLTE.min.css') }}">
    <script src="{{ asset('js/jquery-2.2.0.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/layout/dist/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
    <header class="main-header">

        <!-- Logo -->
        <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>LT</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Nome do </b>SOFTWARE</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

            <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="user-options" aria-expanded="false">
              <span class="hidden-xs">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <p>
                  {{ Auth::user()->name }} - Cargo {{ Auth::user()->tipo }}
                                    <small>{{ Auth::user()->created_at->format('d/m/Y') }}</small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('alterar.usuario', Auth::user()->id) }}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-default btn-flat">Sair</a>
                </div>
              </li>
            </ul>
          </li>

                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">Menu</li>

                <li class="{{str_contains(Request::path(),['pessoas', 'produtos'])?'active':''}} treeview">
                    <a href=""><i class="fa fa-wrench  sidebar-nav-icon"></i> <span>Manutenção</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>

                    <ul class="treeview-menu">
                        <li class="{{str_contains(Request::path(),['pessoas'])?'active':''}} treeview">
                            <a href="#"><i class="fa fa-user sidebar-nav-icon"></i> Pessoas
                                <span class="pull-right-container"><i
                                            class="fa fa-angle-left pull-right"></i></span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('pessoas.incluir') }}">Cadastrar</a></li>
                                <li><a href="{{ route('pessoas.listar') }}">Listar</a></li>
                            </ul>
                        </li>
                        <li class="{{str_contains(Request::path(),['produtos'])?'active':''}} treeview">
                            <a href="#"><i class="fa fa-user sidebar-nav-icon"></i> Produto
                                <span class="pull-right-container"><i
                                            class="fa fa-angle-left pull-right"></i></span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('produtos.incluir') }}">Cadastrar</a></li>
                                <li><a href="{{ route('produtos.listar') }}">Listar</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>



                <li class="{{str_contains(Request::path(),['pedidos'])?'active':''}} treeview">
                    <a href=""><i class="fa fa-exchange sidebar-nav-icon"></i> <span>Movimentações</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>

                    <ul class="treeview-menu">
                        <li class="{{str_contains(Request::path(),['pedidos'])?'active':''}} treeview">
                            <a href="#"><i class="fa fa-shopping-cart sidebar-nav-icon"></i> Pedido
                                <span class="pull-right-container"><i
                                            class="fa fa-angle-left pull-right"></i></span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('pedidos.incluir') }}">Novo</a></li>
                                <li><a href="{{ route('pedidos.listar') }}">Listar</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>



                <li class="{{str_contains(Request::path(),['contas', 'movimentacao'])?'active':''}} treeview">
                    <a href=""><i class="fa fa-money  sidebar-nav-icon"></i> <span>Financeiro</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>

                    <ul class="treeview-menu">
                        <li class="{{str_contains(Request::path(),['contas/receber'])?'active':''}} treeview">
                            <a href="#"><i class="fa fa-plus sidebar-nav-icon"></i> Contas receber
                                <span class="pull-right-container"><i
                                            class="fa fa-angle-left pull-right"></i></span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('contas.receber.incluir') }}">Cadastrar</a></li>
                                <li><a href="{{ route('contas.receber.listar') }}">Listar</a></li>
                            </ul>
                        </li>
                        @if(Auth::user()->tipo == 'gerente')
                            <li class="{{str_contains(Request::path(),['contas/pagar'])?'active':''}} treeview">
                                <a href="#"><i class="fa  fa-minus sidebar-nav-icon"></i> Conta pagar
                                    <span class="pull-right-container"><i
                                            class="fa fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{ route('contas.pagar.incluir') }}">Cadastrar</a></li>
                                    <li><a href="{{ route('contas.pagar.listar') }}">Listar</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('movimentacao.listar') }}"><i class="fa fa-circle-o"></i> Controle de caixa</a>
                            </li>
                        @endif
                    </ul>
                </li>

                @if(Auth::user()->tipo == 'gerente')
                    <li><a href="{{ route('cadastrar.usuario') }}"><i class="fa fa-book"></i>
                            <span>Cadastro de usuários</span></a>
                    </li>

                    <li><a href="{{ route('relatorio.comissao') }}"><i class="fa fa-book"></i>
                            <span>Relatório comissões</span></a>
                    </li>
                @endif
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @if(Request::is('/') != 1)
                <div class="content-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="header-section text-center">
                                @if(substr(Request::path(), 0, 15) == 'pedidos/alterar' || substr(Request::path(), 0, 29) == 'contas/parcelas/buscaParcelas')
                                    <h1>{{\Artesaos\SEOTools\Facades\SEOTools::metatags()->getTitleSession() }}
                                        <br> {{ \Artesaos\SEOTools\Facades\SEOTools::metatags()->getDescription()}}</h1>
                                @else
                                    <h1>{{\Artesaos\SEOTools\Facades\SEOTools::metatags()->getTitleSession() }}</h1>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div id="mensagem-aviso">
                @if(Session::has('info'))
                    <div class="alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{Session::get('info')}}
                    </div>
                @endif

                @if(Session::has('warning'))
                    <div class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{Session::get('warning')}}
                    </div>
                @endif

                @if(Session::has('sucesso'))
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{Session::get('sucesso')}}
                    </div>
                @endif

                @if(Session::has('erro'))
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {!! nl2br(Session::get('erro')) !!}
                    </div>
                @endif
            </div>
        </section>

        <section class="content container-fluid">
            @if ( $errors->any() )
                <ul class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    @foreach($errors->all() as $error)
                        <li> {{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <div class="row">
                <div class="container">
                    @yield('conteudo')
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Software ERP
        </div>
        <!-- Default to the left -->
        <strong>Mauricio Treviso Gomes &copy; 2018 <a href="#">E-mail</a>:</strong> mauriciogomes276@gmail.com
    </footer>
</div>


<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/select2.js') }}"></script>
<script src="{{ asset('layout/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{ asset('layout/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('layout/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('layout/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('layout/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
@if(!str_contains(Request::path(),['pedidos']))
    <script>
        $(document).ready(function () {

            $('.select2').select2({
                width: '95%',
                allow_single_deselect: true,
                placeholder: "Seleciona uma opção."
            });
        });
    </script>
@endif
<div class="modal modal-info fade in" id="modal-deletar" style="display: none; padding-right: 15px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" name="modal-href">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja realizar esta operação? <br> Não será possível reverter</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Não</button>
                <button type="button" class="btn btn-outline btn-confirm">Sim</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-warning fade in" id="modal-danger" style="display: none; padding-right: 15px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Atenção!</h4>
            </div>
            <div class="modal-body text">
                <p>One fine body…One fine body…</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-success fade in" id="modal-success" style="display: none; padding-right: 15px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Successo!</h4>
            </div>
            <div class="modal-body text">
                <p>One fine body…</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline reload" data-dismiss="modal">Fechar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal modal-success fade in" id="modal-success-sem-reload" style="display: none; padding-right: 15px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Successo!</h4>
            </div>
            <div class="modal-body text">
                <p>One fine body…</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Fechar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

</body>

</html>