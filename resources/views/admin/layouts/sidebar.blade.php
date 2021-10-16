<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="admin/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Adminstator</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
       
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="active treeview menu-open">
                <a href="{{ url('/admin/dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="active"><a href="{{ url('/admin/dashboard') }}"><i class="fa fa-circle-o"></i> Dashboard </a></li>
                </ul>
            </li>

            <!-- Widgets dùng để quản lý các thành phần của website như header, sidebar footer -->
            <li>
                <a href="{{ url('/admin/customers/') }}">
                    <i class="fa fa-circle-o"></i> <span>Customers</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/editors/') }}">
                    <i class="fa fa-circle-o"></i> <span>Editors</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/qc/') }}">
                    <i class="fa fa-circle-o"></i> <span>QC</span>
                </a>
            </li>
            <li><a target="_blank" href="https://docs.google.com/document/d/15npdEv7eAbYIBWHIBrbizTHAxFuaYMnbM3m7tLBMFGE/edit?fbclid=IwAR2Vck1YYqo6ByDxpSOfCCHg0-A9kqAarme5Aeta711VtnCxmgF7bs_7q9w"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>