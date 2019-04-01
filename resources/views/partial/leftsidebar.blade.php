<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('appimages/dp/'.Auth::User()->photo) }}" style="height: 40px; width:40px;" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{Auth::User()->name}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <!--<form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>-->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li>
          <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> <span>Home</span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i>
            <span>Siswa</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('student.page') }}"><i class="fa fa-circle-o"></i> Daftar Siswa</a></li>
            @if(in_array(Auth::User()->level, [1,2]))<li><a href="{{ route('student.add') }}"><i class="fa fa-circle-o"></i> Tambah Siswa</a></li>@endif
            <!--<li><a href="../layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>-->
            <!--<li><a href="../layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>-->
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-user-circle-o"></i>
            <span>Koordinator</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('coordinator.page') }}"><i class="fa fa-circle-o"></i> Daftar Koordinator</a></li>
            <!--<li><a href="../layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>-->
            <!--<li><a href="../layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>-->
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-money"></i>
            <span>Donasi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('donate.page') }}"><i class="fa fa-circle-o"></i> Daftar Donasi</a></li>
            <!--<li><a href="../layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>-->
            <!--<li><a href="../layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>-->
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-id-card"></i>
            <span>Sponsor</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('sponsor.page') }}"><i class="fa fa-circle-o"></i> Daftar Sponsor</a></li>
            <!--<li><a href="../layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>-->
            <!--<li><a href="../layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>-->
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-user"></i>
            <span>User</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if(in_array(Auth::User()->level, [1,2]))<li><a href="{{ route('user.page') }}"><i class="fa fa-circle-o"></i> Daftar User</a></li>@endif
            <li><a href="{{ route('user.profile',Auth::user()->id) }}"><i class="fa fa-circle-o"></i> Profil</a></li>
            <!--<li><a href="../layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>-->
            <!--<li><a href="../layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>-->
          </ul>
        </li>
        @if(in_array(Auth::User()->level, [1,2]))
        <li class="treeview">
          <a href="#">
            <i class="fa fa-gears"></i>
            <span>Pengaturan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('religion.page') }}"><i class="fa fa-circle-o"></i> Agama</a></li>
            <li><a href="{{ route('nation.page') }}"><i class="fa fa-circle-o"></i> Negara</a></li>
            <li><a href="{{ route('card.page') }}"><i class="fa fa-circle-o"></i> Kartu ID</a></li>
            <li><a href="{{ route('education.page') }}"><i class="fa fa-circle-o"></i> Pendidikan</a></li>
            <li><a href="{{ route('occupation.page') }}"><i class="fa fa-circle-o"></i> Pekerjaan</a></li>
            <li><a href="{{ route('year.page') }}"><i class="fa fa-circle-o"></i> Tahun Ajaran</a></li>
            <!--<li><a href="../layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>-->
            <!--<li><a href="../layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>-->
          </ul>
        </li>
        @endif
        <!--
        <li>
          <a href="../widgets.html">
            <i class="fa fa-th"></i> <span>Koordinator</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Sponsor</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="../charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
            <li><a href="../charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
            <li><a href="../charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
            <li><a href="../charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>Data Mutasi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="../UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
            <li><a href="../UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
            <li><a href="../UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
            <li><a href="../UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
            <li><a href="../UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
            <li><a href="../UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
          </ul>
        </li>
        -->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>