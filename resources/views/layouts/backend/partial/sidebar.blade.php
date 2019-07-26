<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="index3.html" class="brand-link">
		<img src="{{ asset('assets/backend/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
		     style="opacity: .8">
		<span class="brand-text font-weight-light">RMS</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="{{ asset('assets/backend/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
			</div>
			<div class="info">
				<a href="#" class="d-block">Alexander Pierce</a>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
					 with font-awesome or any other icon font library -->
				@if(Request::is('register*')))
					<li class="nav-item has-treeview">
						<a href="{{ route('register.dashboard') }}" class="nav-link {{ Request::is('register/dashboard') ? 'active' : '' }}">
							<i class="nav-icon fa fa-dashboard"></i>
							<p>
								Dashboard
							</p>
						</a>
					</li>
					<li class="nav-item has-treeview {{ Request::is('register/dept*') ? 'menu-open' : '' }}">
						<a href="#" class="nav-link {{ Request::is('register/dept*') ? 'active' : '' }}">
							<i class="nav-icon fa fa-pie-chart"></i>
							<p>
								Departments
								<i class="right fa fa-angle-left"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="{{ route('register.dept.create') }}" class="nav-link {{ Request::is('register/dept/create') ? 'active' : '' }}">
									<i class="fa fa-circle-o nav-icon"></i>
									<p>Add Department</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="{{ route('register.dept.index') }}" class="nav-link {{ Request::is('register/dept') ? 'active' : '' }}">
									<i class="fa fa-circle-o nav-icon"></i>
									<p>All Depts</p>
								</a>
							</li>
						</ul>
					</li>

				<li class="nav-item has-treeview {{ Request::is('register/hall*') ? 'menu-open' : '' }}">
					<a href="#" class="nav-link {{ Request::is('register/hall*') ? 'active' : '' }}">
						<i class="nav-icon fa fa-pie-chart"></i>
						<p>
							Halls
							<i class="right fa fa-angle-left"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ route('register.hall.create') }}" class="nav-link {{ Request::is('register/hall/create') ? 'active' : '' }}">
								<i class="fa fa-circle-o nav-icon"></i>
								<p>Add Hall</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ route('register.hall.index') }}" class="nav-link {{ Request::is('register/hall') ? 'active' : '' }}">
								<i class="fa fa-circle-o nav-icon"></i>
								<p>All Halls</p>
							</a>
						</li>
					</ul>
				</li>

				@elseif(Request::is('exam-controller*')))


				@elseif(Request::is('dept-office*')))

				@elseif(Request::is('teacher*')))

				@elseif(Request::is('student*')))

				@endif

				<li class="nav-header">MENU</li>

				<li class="nav-item">
					<a class="nav-link" href="{{ route('logout') }}"
					   onclick="event.preventDefault();
					   document.getElementById('logout-form').submit();">
						<i class="nav-icon fa fa-sign-out"></i> Logout
					</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					</form>


				</li>

			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>