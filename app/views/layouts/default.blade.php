e html>

<!-- CODE REVIEW: remove this file -->

<html>
	<head>
		<meta charset="utf-8">
		<title>Tarlac Procurement Tracking System</title>

		{{ HTML::style('css/bootstrap.min.css') }}
		{{ HTML::style('css/bootstrap-theme.min.css') }}
		{{ HTML::style('css/theme.css') }}
		{{ HTML::style('css/signin.css') }}

		{{ HTML::style('css/custom.css') }}

		{{ HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}

		{{ HTML::script('js/bootstrap.min.js') }}

	<!-- Agile Image Uploader-->
		{{ HTML::script('jquery-1.4.min.js')}}
		{{ HTML::script('jquery.flash.min.js')}}
		{{ HTML::script('agile-uploader-3.0.js')}}
		{{ HTML::style('agile-uploader.css') }}

		{{ HTML::style('colvix/css/jquery.dataTables.css')}}
		{{ HTML::style('colvix/css/dataTables.colVis.css')}}
		{{ HTML::style('colvix/css/shCore.css')}}
		

		{{ HTML::script('colvix/js/jquery.js')}}
		{{ HTML::script('colvix/js/jquery.dataTables.js')}}
		{{ HTML::script('colvix/js/dataTables.colVis.js')}}
		{{ HTML::script('colvix/js/shCore.js')}}
		{{ HTML::script('colvix/js/demo.js')}}
		{{ HTML::script('js/bootstrap-dropdown.js') }}
<!--Hover Image Overlay  -->

		@yield('header')
<script type = "text/javascript">
	function show(elementId) { 
  //now we kick out both conditional we do not need them anymore

  //we hide both forms

  //and then we simply show wanted one isn't that nicer and cleaner?
  document.getElementById(elementId).style.display="block";
}
		</script>
		<script type="text/javascript" language="javascript" class="init">
			$(document).ready(function() {
				$('#table_id').DataTable( {
					dom: 'C<"clear">lfrtip'
				} );
			} );
		</script>

		<style>
			body { background-image:url('../bg.png'); }
		</style>
	</head>
	<body role="document">


		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	    	<div class="container">
	    		<div class="navbar-header">
			        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        </button>
			        <a class="navbar-brand" href="{{ URL::to('/') }}">PTS</a>
	        	</div>
	        	<div class="navbar-collapse collapse">
          			<ul class="nav navbar-nav">
          				<!--change request path for PRs and Users -->
          				<li class="{{Request::is('purchase_requests') ? 'active':''}}">{{ link_to('/purchaseRequest/view', 'Purchase Requests') }}</li>
          				@if ( Entrust::hasRole('Administrator') || Entrust::hasRole('Procurement Personnel'))
          				<li class="{{Request::is('user') ? 'active':''}}">{{ link_to('/user/view', 'Users') }}</li>
          				<li class="{{Request::is('offices') ? 'active':''}}">{{ link_to('/offices', 'Offices') }}</li>
          				<!--li class="{{Request::is('workflow') ? 'active':''}}">{{ link_to('/workflow', 'Workflow') }}</li-->
          				<li class="{{Request::is('workflow') ? 'active':''}} dropdown">
          					<a href="#" class="dropdown" data-toggle="dropdown">Workflow <b class="caret"></b></a>
          					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu2">
							  <li>{{ link_to('/workflow/belowFifty', 'Below P50,000') }}</li>
							  <li>{{ link_to('/workflow/aboveFifty', 'Between P50,000 and P500,000') }}</li>
							  <li>{{ link_to('/workflow/aboveFive', 'Above P500,000') }}</li>
							</ul>
          				</li>
          				<li class="{{Request::is('designation') ? 'active':''}}">{{ link_to('/designation', 'Designations') }}</li>
          				@endif
          			</ul>
          			<ul class="nav navbar-nav">
          				<li></li>
          			</ul>
          			<ul class="nav navbar-nav navbar-right">
			        	<li><a href="{{ URL::to('/logout') }}" class="glyphicon glyphicon-log-out" title="Logout"><span id="logout">Logout</span></a></li>
			        </ul>
          		</div>
	        </div>
	    </div>
	    <div class="container theme-showcase" role="main">
			@yield('content')
		</div>
		
		@yield('footer')
		
		<footer class="bs-docs-footer" role="contentinfo">
			<div class="container">
				<p id="copyright-section">Developed by {{ link_to('http://solutionsresource.com/', 'Solutions Resource, Inc.') }} All rights reserved. © 2014</p>
			</div>
		</footer>
	

	</body>
</html>