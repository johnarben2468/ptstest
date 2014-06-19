<!doctype html>
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
	



		{{ HTML::style('colvix/css/jquery.dataTables.css')}}
		{{ HTML::style('colvix/css/dataTables.colVis.css')}}
		{{ HTML::style('colvix/css/shCore.css')}}
		

		{{ HTML::script('colvix/js/jquery.js')}}
		{{ HTML::script('colvix/js/jquery.dataTables.js')}}
		{{ HTML::script('colvix/js/dataTables.colVis.js')}}
		{{ HTML::script('colvix/js/shCore.js')}}
		{{ HTML::script('colvix/js/demo.js')}}
		{{ HTML::script('js/bootstrap-dropdown.js') }}

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
          				<li><a>Puchase Requests</a></li>
          				<li><a>Users</a></li>
          				<li>{{ link_to('/offices', 'Offices') }}</li>
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
		<script type="text/javascript">
			@yield('footer')
		</script>
		<footer class="bs-docs-footer" role="contentinfo">
			<div class="container">
				<p id="copyright-section">Developed by {{ link_to('http://solutionsresource.com/', 'Solutions Resource, Inc.') }} All rights reserved. © 2014</p>
			</div>
		</footer>
	</body>
</html>