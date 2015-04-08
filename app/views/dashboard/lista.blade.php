@extends('layouts.master')

@section('breadcrumb')
<ul class="breadcrumb">
            <li>
              <i class="fa fa-home home-fa fa"></i>
              <a href="#">Home</a>

              <span class="divider">
                <i class="fa fa-angle-right arrow-fa fa"></i>
              </span>
            </li>

            <li>
              <a href={{ URL::to('orden') }}>orden</a>

              <span class="divider">
                <i class="fa fa-angle-right arrow-fa fa"></i>
              </span>
            </li>
            <li>Ver orden</li>
          </ul><!--.breadcrumb-->

          @stop

@section('contenido')


<div class="page-header position-relative">
<h1>
							Inicio
							<small>
								<i class="fa fa-angle-double-right"></i>

								
							</small>
						</h1>
					</div>

@if(isset($mensaje2))
<div class="alert alert-block alert-warning">
								<button type="button" class="close" data-dismiss="alert">
									<i class="fa fa-remove"></i>
								</button>

				

							{{$mensaje2}}

							</div>
@endif

@if(isset(Session::get('proyecto')->nombre))
<div class="alert alert-block alert-success">
								<button type="button" class="close" data-dismiss="alert">
									<i class="fa fa-remove"></i>
								</button>

				

							Has seleccionado el proyecto {{Session::get('proyecto')->nombre}}
							

							</div>

@else



<div class="alert alert-block alert-success">
								<button type="button" class="close" data-dismiss="alert">
									<i class="fa fa-remove"></i>
								</button>

								<i class="fa fa-ok green"></i>

								Bienvenido
								<strong class="green">
									{{ Auth::user()->nombre }}
									{{ Auth::user()->apellido }}
									<small>Constructora CGS (v 0.0.1)</small>
								</strong>
Panel de control
							</div>



							<div class="alert alert-block alert-danger">
								<button type="button" class="close" data-dismiss="alert">
									<i class="fa fa-remove"></i>
								</button>

								<i class="fa fa-ok green"></i>

								<strong>Primero debes seleccionar un proyecto, haz click en la imagen.</strong>
								

							</div>

	@endif				


							<div class="hr hr32 hr-dotted"></div>


							
							

							<div class="row-fluid">
								@foreach($proyectos as $proyecto)
								<div class="span4">
								<div class="widget-box">
											<div class="widget-header">
												<h5 class="widget-title">{{$proyecto->nombre}}</h5>

												
											</div>

											<div class="widget-body">
												<div class="widget-main center">
														<span class="profile-picture">
														<a href="{{URL::to('proyectos/session/'.$proyecto->id)}}"><img src={{asset('img/'.$proyecto->img)}} alt="Logo" width='400'></a>
														</span>
												
												</div>
											</div>
										</div>
									</div>

									@endforeach
							</div>

							



							</div>

							<div class="hr hr32 hr-dotted"></div>

							<div class="row-fluid">
								<div class="span6">
						
								</div><!--/span-->

								<div class="span6">
									
								</div><!--/span-->
							</div><!--/row-->







@stop
