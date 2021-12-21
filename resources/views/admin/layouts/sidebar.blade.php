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
            <li>
                <a href="{{ url('/admin/dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <!-- Widgets dùng để quản lý các thành phần của website như header, sidebar footer -->
            <li>
                <a href="{{ url('/admin/groups/') }}">
                    <i class="fa fa-group"></i> <span>Groups</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/customers/') }}">
                    <i class="fa fa-user"></i> <span>Customers</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/editors/') }}">
                    <i class="fa fa-pencil"></i> <span>Editors</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/qc/') }}">
                    <i class="fa fa-user-circle"></i> <span>QC</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/jobs/') }}">
                    <i class="fa fa-link"></i> <span>List jobs</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/sub-admin/') }}">
                    <i class="fa fa-child"></i> <span>Sub admin</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/notifications/') }}">
                    <i class="fa fa-bell"></i> <span>Notifications</span>
                </a>
            </li>
            <li><a target="_blank" href="https://docs.google.com/document/d/15npdEv7eAbYIBWHIBrbizTHAxFuaYMnbM3m7tLBMFGE/edit?fbclid=IwAR2Vck1YYqo6ByDxpSOfCCHg0-A9kqAarme5Aeta711VtnCxmgF7bs_7q9w"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
