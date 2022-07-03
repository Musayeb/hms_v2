
<!doctype html>
<html lang="en" dir="ltr">
	<head>

		<!-- META DATA -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		
		<!-- FAVICON -->
		<link rel="shortcut icon" type="image/x-icon" href="{{asset('public/assets/images/brand/favicon.ico')}}" />

		<!-- TITLE -->
		<title>HMS- @yield('title')</title>

		<!-- BOOTSTRAP CSS -->
		<link href="{{asset('public/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />

		<!-- STYLE CSS -->
		<link href="{{asset('public/assets/css/style.css')}}" rel="stylesheet"/>
		<link href="{{asset('public/assets/css/skin-modes.css')}}" rel="stylesheet"/>

		<!-- SIDE-MENU CSS -->
		<link href="{{asset('public/assets/css/sidemenu.css')}}" rel="stylesheet">
		<link href="{{asset('public/assets/css/icons.css')}}" rel="stylesheet"/>

	<!--- FONT-ICONS CSS -->
		<link href="{{asset('public/assets/offline/offline.css')}}" rel="stylesheet"/>
		<link href="{{asset('public/assets/offline/offline-theme-chrome.css')}}" rel="stylesheet"/>
		<link href="{{asset('public/assets/offline/offline-language-english.css')}}" rel="stylesheet"/> 

		<link href="{{asset('public/assets/calculator/style.css')}}" rel="stylesheet"/>
		{{-- <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css"> --}}

		<!-- COLOR SKIN CSS -->
		<link id="theme" rel="stylesheet" type="text/css" media="all" href="{{asset('public/assets/colors/color1.css')}}" />
		@yield('css')
	</head>
	<body class="app sidebar-mini Left-menu-Default  Sidemenu-left-icons">
		<!-- GLOBAL-LOADER -->
		<div id="global-loader">
			<img src="{{asset('public/assets/images/loader.svg')}}" class="loader-img" alt="Loader">
		</div>
		<!-- /GLOBAL-LOADER -->
		

		<!-- PAGE -->
		<div class="page">
			<div class="page-main">

				<!--APP-SIDEBAR-->
				<div class="app-header header-search-icon">
					<div class="header-style1">
						<a class="header-brand" href="{{url('/dashboard')}}">
							<img src="{{asset('public/hospital.jpg')}}" class="header-brand-img desktop-logo" alt="logo" style="width: 130px">
							<img src="{{asset('public/hospital.jpg')}}" class="header-brand-img mobile-logo" alt="logo">
						</a><!-- LOGO -->
					</div>
					<div class="app-sidebar__toggle" data-toggle="sidebar">
						<a class="open-toggle" href="#"><i class="fe fe-align-left"></i></a>
						<a class="close-toggle" href="#"><i class="fe fe-x"></i></a>
					</div>
					<div class="ml-5">
						@yield('direct_btn')
					</div>
					<div class="d-flex  ml-auto header-right-icons">
						<div class="dropdown d-md-flex">
							<a class="nav-link icon nav-link-bg bg-primary" data-toggle="modal" data-target="#calc-modal">
								<i class="fa fa-calculator text-white" aria-hidden="true"></i>
							</a>
							<a class="nav-link icon full-screen-link nav-link-bg">
								<i class="fe fe-minimize fullscreen-button"></i>
							</a>
					
						</div><!-- FULL-SCREEN -->

						<div class="dropdown profile-1">
							<a href="#" data-toggle="dropdown" class="nav-link pr-2 leading-none d-flex">
								<span>
								    @if (empty(Auth()->user()->profile_photo_path))
                                <img src="{{url('public/assets/images/icon.jpg')}}" alt="user" style="width:40px;height:40px;border-radius:50px;" >
                               @else
                                   <img src="{{url('storage/app/avatar')}}/{{Auth()->user()->profile_photo_path}}" alt="user" style="width:40px;height:40px;border-radius:50px;">
                               @endif

								</span>
							</a>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
								<div class="drop-heading">
									<div class="text-center">
										@if (empty(Auth()->user()->profile_photo_path))
										<img src="{{url('public/assets/images/icon.jpg')}}" alt="user" style="width:40px;height:40px;border-radius:50px;" >
									   @else
										   <img src="{{url('storage/app/avatar')}}/{{Auth()->user()->profile_photo_path}}" alt="user" style="width:40px;height:40px;border-radius:50px;">
									   @endif
										<h5 class="text-dark mb-0">{{Auth()->user()->name}}</h5>
										<small class="text-muted">@php
											$role=Helper::getNameRole(Auth()->user()->role_id);
											echo $role[0]->name;
										@endphp</small>
									</div>
								</div>
								<div class="dropdown-divider m-0"></div>
							
								<a class="dropdown-item" href="{{url('settings')}}">
									<i class="dropdown-icon  mdi mdi-settings"></i> Settings
								</a>
			
								<div class="dropdown-divider mt-0"></div>
					
								<a class="dropdown-item" href="{{url('/logout')}}">
									<i class="dropdown-icon mdi  mdi-logout-variant"></i> Sign out
								</a>
							</div>
						</div>

					</div>
				</div>
				<!--APP-SIDEBAR-->

				<!--APP-SIDEBAR-->
				<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
				<aside class="app-sidebar">

					<ul class="side-menu">
						<li><h3>Menu</h3></li>
					@if (!empty(Helper::getpermission('access_patients')))
						
					<li>
						<a class="side-menu__item mt-2" href="{{url('patients')}}">
						<span class="side-menu__label">Patinets</span><i class="side-menu__icon fa fa-hotel"></i></a>
					</li>
					@endif
					
					@if (!empty(Helper::getpermission('access_humanResourcess')))
				
					<li>
						<a class="side-menu__item" data-toggle="slide" href="#"><i class="angle fe fe-chevron-right"></i><span class="side-menu__label">Human Resources</span><i class="side-menu__icon fa fa-id-card-o"></i></a>
							<ul class="slide-menu">
								@if (!empty(Helper::getpermission('_access_employee')))

								<li><a href="{{url('employees')}}" class="slide-item"><i class="sidemenu-icon fe fe-chevrons-right"></i> Employee </a></li>
								@endif

							</ul>
					</li>
					@endif

					@if (!empty(Helper::getpermission('access_appoinments')))
					<li>
						<a class="side-menu__item mt-2" href="{{url('appoinments')}}">
						<span class="side-menu__label">Appoinments</span><i class="side-menu__icon fa fa-calendar"></i></a>
					</li>
					@endif
					
					@if (!empty(Helper::getpermission('access_opd')))
					<li>
						<a class="side-menu__item mt-2" href="{{url('opd')}}">
						<span class="side-menu__label">OPD</span><i class="side-menu__icon fa fa-stethoscope"></i></a>
					</li>
					@endif
					@if (!empty(Helper::getpermission('access_stock')))
					<li>
						<a class="side-menu__item" data-toggle="slide" href="#"><i class="angle fe fe-chevron-right"></i><span class="side-menu__label">Stock Managment</span><i class="side-menu__icon fa fa-cart-arrow-down"></i></a>
							<ul class="slide-menu">
								
							@if (!empty(Helper::getpermission('access_pharmacy')))
								<li>
									<a class="side-menu__item" data-toggle="slide" href="#"><i class="angle fe fe-chevron-right"></i><span class="side-menu__label">Pharmacy</span><i class="side-menu__icon fe fe-activity"></i></a>
										<ul class="slide-menu">
											
				    			     @if (!empty(Helper::getpermission('_access_purchaseMedicines')))
											<li><a href="{{url('purchase-mediciens')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Purchase Midicines </a></li>
									 @endif
									 @if (!empty(Helper::getpermission('_access_medicines')))
											<li><a href="{{url('medicines')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i> Medicines </a></li>
									@endif	
											@if (!empty(Helper::getpermission('_access_medicineCatagory')))
											
											<li><a href="{{url('medicines_cat')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i> Medicine Catagory </a></li>
											@endif
										</ul>
								</li>
							@endif
							
							@if (!empty(Helper::getpermission('access_laboratory')))

								<li>
									<a class="side-menu__item" data-toggle="slide" href="#"><i class="angle fe fe-chevron-right"></i><span class="side-menu__label">Laboratory</span><i class="side-menu__icon fe fe-activity"></i></a>
										<ul class="slide-menu">
											@if (!empty(Helper::getpermission('_access_purchaseLabMaterial')))
											<li><a href="{{url('lab-purchase-materials')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Purchase Laboratory Materials </a></li>
											@endif											
											@if (!empty(Helper::getpermission('_access_labMaterials')))
											<li><a href="{{url('lab-materials')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Laboratory Materials </a></li>
											@endif
											@if (!empty(Helper::getpermission('_access_LabMaterialCategory')))
											<li><a href="{{url('lab-cat')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i> Laboratory Materials Catagory </a></li>
										@endif
										</ul>
								</li>
							@endif	
							</ul>
					</li>
					@endif
					
					@if (!empty(Helper::getpermission('_access_surgery&Delivery')))
					<li>
						<a class="side-menu__item mt-2" href="{{url('surgery_registration')}}">
						<span class="side-menu__label">Surgery & Delivery</span><i class="side-menu__icon fa fa-cut"></i></a>
					</li>
					@endif
					@if (!empty(Helper::getpermission('access_birth_and_death')))									
					<li>
						<a class="side-menu__item" data-toggle="slide" href="#"><i class="angle fe fe-chevron-right"></i><span class="side-menu__label">Birth & Death Record</span><i class="side-menu__icon fa fa-birthday-cake"></i></a>
						<ul class="slide-menu">
							
							@if (!empty(Helper::getpermission('_access_birth')))
							<li><a href="{{url('birth-record')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i> Birth Record </a></li>
				        	@endif
							@if (!empty(Helper::getpermission('_access_death')))
							<li><a href="{{url('death-record')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i> Death Record</a></li>
				        	@endif
						
						</ul>
					</li>
					@endif
					
					@if (!empty(Helper::getpermission('access_bloodDonation')))									
					<li>
						<a class="side-menu__item mt-2" href="{{url('blood-donation')}}">
						<span class="side-menu__label">Blood Donation</span><i class="side-menu__icon fa fa-tint"></i></a>
					</li>
					@endif
					
					@if (!empty(Helper::getpermission('access_billings')))									
					<li>
						<a class="side-menu__item" data-toggle="slide" href="#"><i class="angle fe fe-chevron-right"></i><span class="side-menu__label">Biling</span><i class="side-menu__icon fa fa-money"></i></a>
							<ul class="slide-menu">
				   	         @if (!empty(Helper::getpermission('_access_pharmacyBilling')))									
								<li><a href="{{url('bill-pharmacy')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Pharmacy Bill</a></li>
							 @endif
					          @if (!empty(Helper::getpermission('_access_laboratoryBilling')))									
								<li><a href="{{url('bill-lab')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Laboratory Bill</a></li>
							  @endif
							 @if (!empty(Helper::getpermission('_access_admissionBilling')))									
							   <li><a href="{{url('admission-bill')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Admission Bill</a></li>
							 @endif 
							 @if (!empty(Helper::getpermission('_access_overTimePaymentBill')))									
								<li><a href="{{url('over_time_payment')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Over Time Payment Bill</a></li>
							@endif
							@if (!empty(Helper::getpermission('_access_nurseBill')))									
							    <li><a href="{{url('nurse_bill')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Nurse Bill</a></li>
							@endif
							@if (!empty(Helper::getpermission('_access_partialPaymentBilling')))									
							   <li><a href="{{url('partial-payment-billing')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Partial Payment Billing</a></li>
							@endif
							@if (!empty(Helper::getpermission('_access_medicalCompanyBilling')))									
								<li><a href="{{url('medical_company_bill')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Medical Company Bill</a></li>
							@endif
							</ul>
					</li>
					@endif
					
					@if (!empty(Helper::getpermission('access_finance')))									
					<li>
						<a class="side-menu__item" data-toggle="slide" href="#"><i class="angle fe fe-chevron-right"></i><span class="side-menu__label">Finance</span><i class="side-menu__icon fa fa-dollar"></i></a>
							<ul class="slide-menu">
								@if (!empty(Helper::getpermission('access_extra_income')))												
								<li><a href="{{url('finance/extra-income')}}" class="slide-item"><i class="sidemenu-icon fe fe-chevrons-right"></i>Extra Income</a></li>
  								@endif
								@if (!empty(Helper::getpermission('_access_endOfTheDay')))									
								<li><a href="{{url('end-of-the-day')}}" class="slide-item"><i class="sidemenu-icon fe fe-chevrons-right"></i>End Of The Day </a></li>
							    @endif
								@if (!empty(Helper::getpermission('_access_payroll')))									
								<li><a href="{{route('payroll.index')}}" class="slide-item"><i class="sidemenu-icon fe fe-chevrons-right"></i>Payroll </a></li>
							    @endif
								@if (!empty(Helper::getpermission('_access_dailyExpenses')))									
								<li><a href="{{url('finance/daily_expenses')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Daily Expenses</a></li>
							    @endif
								@if (!empty(Helper::getpermission('_access_financialStatment')))									
								<li><a href="{{url('finance/statment')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Financial Statment</a></li>
								@endif
							
							</ul>
					</li>
					@endif
						@if (!empty(Helper::getpermission('access_userManagement')))
						<li>
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="angle fe fe-chevron-right"></i><span class="side-menu__label">User Management</span><i class="side-menu__icon fa fa-user"></i></a>
								<ul class="slide-menu">
									@if (!empty(Helper::getpermission('_access_users')))
										<li><a href="{{url('users')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Users</a></li>
										<li><a href="{{url('users_log')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Users Log</a></li>
									@endif
									@if (!empty(Helper::getpermission('_access_permissions')))
										<li><a href="{{url('permissions')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Permissions</a></li>
									@endif
									@if (!empty(Helper::getpermission('_access_roles')))
										<li><a href="{{url('roles')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Roles</a></li>
									@endif
								</ul>
						</li>
					@endif
					
					@if (!empty(Helper::getpermission('access_reports')))
					<li>
						<a class="side-menu__item" data-toggle="slide" href="#"><i class="angle fe fe-chevron-right"></i><span class="side-menu__label">Reports</span><i class="side-menu__icon fa fa-pie-chart"></i></a>
							<ul class="slide-menu">
								<li><a href="{{url('opd-report')}}" class="slide-item"><i class="sidemenu-icon fe fe-chevrons-right"></i>OPD Report</a></li>
								<li><a href="{{url('pharmacy-report')}}" class="slide-item"><i class="sidemenu-icon fe fe-chevrons-right"></i>Pharmacy Report</a></li>
								<li><a href="{{url('laboratory-report')}}" class="slide-item"><i class="sidemenu-icon fe fe-chevrons-right"></i>Laboratory Report</a></li>
							</ul>
					</li>
					@endif

					<li>
						<a class="side-menu__item" data-toggle="slide" href="#"><i class="angle fe fe-chevron-right"></i><span class="side-menu__label">System Setup</span><i class="side-menu__icon mdi mdi-settings"></i></a>
							<ul class="slide-menu">
								
							@if (!empty(Helper::getpermission('_access_departments')))
								<li><a href="{{url('departments')}}" class="slide-item"><i class="sidemenu-icon fe fe-chevrons-right"></i>Departments </a></li>
					        @endif		
					        	@if (!empty(Helper::getpermission('_access_rooms')))
								<li><a href="{{url('room')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Room</a></li>
							@endif	
							@if (!empty(Helper::getpermission('_access_surgery')))
								<li><a href="{{url('surgery')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Surgery Managment</a></li>
					        @endif			
								@if (!empty(Helper::getpermission('_access_procedures')))
								<li><a href="{{url('procedure')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Procedures Managment</a></li>
							@endif	
						 	@if (!empty(Helper::getpermission('_access_tests')))
								<li><a href="{{url('test')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Tests Managment</a></li>
							@endif
							@if (!empty(Helper::getpermission('access_filter')))
								<li><a href="{{url('filter')}}" class="slide-item"><i class="sidemenu-icon  fe fe-chevrons-right"></i>Filter Managment</a></li>
					    	@endif
							</ul>
					</li>
						
				
			
					</ul>
				</aside>
				<!--/APP-SIDEBAR-->

				<!-- Mobile Header -->
				<div class="mobile-header">
					<div class="container-fluid">
						<div class="d-flex">
							<div class="app-sidebar__toggle" data-toggle="sidebar">
								<a class="open-toggle" href="#"><i class="fe fe-align-left"></i></a>
								<a class="close-toggle" href="#"><i class="fe fe-x"></i></a>
							</div>
							<a class="header-brand" href="{{url('/')}}">
								<img src="{{asset('public/hospital.jpg')}}" class="header-brand-img mobile-logo" alt="logo">
							</a><!-- LOGO -->
							<div class="d-flex order-lg-2 ml-auto header-right-icons">
							
								<div class="dropdown profile-1">
									<a href="#" data-toggle="dropdown" class="nav-link pr-2 leading-none d-flex">
										<span>
											@if (empty(Auth()->user()->profile_photo_path))
											<img src="{{url('public/assets/images/icon.jpg')}}" alt="user" style="width:40px;height:40px;border-radius:50px;" >
										   @else
											   <img src="{{url('storage/app/avatar')}}/{{Auth()->user()->profile_photo_path}}" alt="user" style="width:40px;height:40px;border-radius:50px;">
										   @endif
										</span>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
										<div class="drop-heading">
											<div class="text-center">
												<h5 class="text-dark mb-0">{{Auth()->user()->name}}</h5>
												<small class="text-muted">@php
													$role=Helper::getNameRole(Auth()->user()->role_id);
													echo $role[0]->name;
												@endphp</small>
												
											</div>
										</div>
										<div class="dropdown-divider m-0"></div>
										<a class="dropdown-item" href="{{url('settings')}}">
											<i class="dropdown-icon  mdi mdi-settings"></i> Settings
										</a>
					
										<div class="dropdown-divider mt-0"></div>
							
										<a class="dropdown-item" href="{{url('/logout')}}">
											<i class="dropdown-icon mdi  mdi-logout-variant"></i> Sign out
										</a>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			
				<!-- /Mobile Header -->

                <!--app-content open-->
				<div class="app-content">
					<div class="side-app">

						<!-- PAGE-HEADER -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
								@yield('directory')
							</ol><!-- End breadcrumb -->

						</div>
						<!-- PAGE-HEADER END -->

						<!-- ROW-1 OPEN -->

						<div>
						<div class="row text-center justify-content-center" ><div class="text-white" id="connection" style="display:none;position: fixed;z-index: 100000;;padding:20px;background-color:rgb(69, 69, 140);width:200px">Internet is Down</div></div>
							
							@yield('content')
						
						</div>
					</div>
					<!-- CONTAINER CLOSED -->
				</div>
			</div>

			<!-- SIDE-BAR CLOSED -->

			<!-- FOOTER -->
			<footer class="footer">
				<div class="container">
					<div class="row align-items-center flex-row-reverse">
						<div class="col-md-12 col-sm-12 text-center">
							Copyright © 2021 <a href="#">HMS</a>. Designed by <a href="https://sarey.co" target="_Blank"> Sarey.co </a> All rights reserved.
						</div>
					</div>
				</div>
			</footer>
			
			<!-- FOOTER CLOSED -->
		</div>
		{{-- modal calculator --}}
		<div class="modal fade" id="calc-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content " style="min-width: 330px;max-width: 330px;">
				
					<div class="modal-body" >
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h1 class="text-center h2 font-weight-600 mb-3">Calculator</h1>
						<div class="cal_container">
							<div class="cal_row">
								<button id="clear" class="cal_button" value="">AC</button>
								<div class="cal_screen"></div>
							</div>
							<div class="cal_row">
								<button class="digit cal_button" value="7">7</button>
								<button class="digit cal_button" value="8">8</button>
								<button class="digit cal_button" value="9">9</button>
								<button class="operation cal_button" id="/">/</button>
							</div>
							<div class="cal_row">
								<button class="cal_button digit" value="4">4</button>
								<button class="digit cal_button" value="5">5</button>
								<button class="digit cal_button" value="6">6</button>
								<button class="operation cal_button" id="-">-</button>
							</div>
							<div class="cal_row">
								<button class="digit cal_button" value="1">1</button>
								<button class="digit cal_button" value="2">2</button>
								<button class="digit cal_button" value="3">3</button>
								<button class="operation cal_button" id="+">+</button>
							</div>
							<div class="cal_row">
								<button class="digit cal_button" value="0">0</button>
								<button class="decPoint cal_button" value=".">.</button>
								<button class="equal cal_button" id="eql">=</button>
								<button class="operation cal_button" id="*">*</button>
							</div>
						</div>
					</div>
				
				</div>
			</div>
		</div

		<!-- BACK-TO-TOP -->
		<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

		<!-- JQUERY JS -->
		<script src="{{asset('public/assets/js/jquery-3.4.1.min.js')}}"></script>
		<!-- BOOTSTRAP JS -->
		<script src="{{asset('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
		<script src="{{asset('public/assets/plugins/bootstrap/js/popper.min.js')}}"></script>
		<!-- SPARKLINE JS-->
		<script src="{{asset('public/assets/js/jquery.sparkline.min.js')}}"></script>
		<!-- Moment js-->
		<script src="{{asset('public/assets/plugins/moment/moment.min.js')}}"></script>
		<!-- CHART-CIRCLE JS-->
		<script src="{{asset('public/assets/js/circle-progress.min.js')}}"></script>
		<!-- SIDE-MENU JS-->
		<script src="{{asset('public/assets/plugins/sidemenu/sidemenu.js')}}"></script>
		<script src="{{asset('public/assets/plugins/sidebar/sidebar.js')}}"></script>
		<!-- CUSTOM JS -->
		<script src="{{asset('public/assets/js/custom.js')}}"></script>
		 <script src="{{asset('public/assets/offline/offline.min.js')}}"></script> 
		 <script src="{{asset('public/assets/calculator/main.js')}}"></script> 
		 {{-- <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script> --}}

        <script>
        // document.oncontextmenu = document.body.oncontextmenu = function() {return false;}
        </script>
		@yield('jquery')
 
		<script>
			 function alignModal() {
                var modalDialog = $(this).find(".modal-dialog");
                modalDialog.css("margin-top", Math.max(0, 
                ($(window).height() - modalDialog.height()) / 2));
            }
            $(".modal").on("shown.bs.modal", alignModal);
		</script>
		{{-- <script>
			var run = function(){
			var req = new XMLHttpRequest();
			req.timeout = 5000;
			req.open('get', '{{url("connection")}}', true);
			req.send();
			}
			setInterval(run, 10000);
			</script>  --}}
	</body>
</html>