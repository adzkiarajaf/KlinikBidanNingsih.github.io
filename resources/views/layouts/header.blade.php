<header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            KBN</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">{{ config ('app.name');  }}
        </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img
                            src="{{ asset('AdminLTE-2/dist/img/ava.jpg') }}"
                            class="user-image"
                            alt="User Image">
                            <span class="hidden-xs">{{  auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img
                                    src="{{ asset('AdminLTE-2/dist/img/ava.jpg') }}"
                                    class="img-circle"
                                    alt="User Image">
                                    <p style="color:rgb(26, 26, 65)">
                                        {{  auth()->user()->name }}
                                        -
                                        {{  auth()->user()->email }}
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="">
                                        <a
                                            href="#"
                                            class="btn btn-warning btn-flat btn-block"
                                            onclick="$('#logout-form').submit()">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <form
            action="{{  route('logout') }}"
            method="post"
            id="logout-form"
            style="display:none">
            @csrf
        </form>