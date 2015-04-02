<?php
class ProyectoController extends BaseController {

public function mostrar(){

	$proyectos = Proyecto::all();

	return View::make("proyectos.lista")->with("proyectos",$proyectos);

}

public function nuevo(){

	$proyectos = new Proyecto;
	return View::make("proyectos.formulario")->with("proyectos",$proyectos);
	//return "ho";
}


public function nuevo2(){

	 $proyectos = Proyecto::create(Input::all());

	 $fechainicio = Input::get("fechainicio");
	list($dia,$mes,$ano) = explode("/",$fechainicio);
	 $fechainicio = "$ano-$mes-$dia";

	  $fechatermino = Input::get("fechatermino");
	list($dia,$mes,$ano) = explode("/",$fechatermino);
	 $fechatermino = "$ano-$mes-$dia";


	 $proyectos->fechainicio = $fechainicio;
	$proyectos->fechatermino = $fechatermino;

	$proyectos->save();


	return Redirect::to("proyectos");
}


public function sessionProyecto($id){
	//Session::put('proyecto', $id);
	

if (Auth::check())
{
	$iduser =  Auth::user()->id;
}

	$permiso = UsuarioProyecto::where("usuario_id","=",$iduser)->where("proyecto_id","=",$id)->get(); //revisando si tiene permiso

	 if(count($permiso) == 0) // no tiene permiso
	 {
	 //	return Redirect::to("dashboard")->with("mensaje","hjolña");
	 	return View::make("dashboard.lista", array("mensaje2"=>"NO TIENES PERMISO PARA ACCEDER A ESTE PROYECTO"));
	 	//Log::warning('Algo podría ir mal.');
	 }  
	 else // si existe (1) tiene permiso
	 {
	 	$proyecto = Proyecto::find($id);
	Session::put('proyecto', $proyecto);
	 	return Redirect::to("controlgasto");
	 }
	
	
}

public function obras(){

	$proyectos = Proyecto::find(1)->obras;

	return View::make("proyectos.lista", array("proyectos"=>$proyectos));

}


public function editar($id){

	$proyecto = Proyecto::find($id);

	return View::make("proyectos.formulario")->with("proyectos",$proyecto);
}

public function editar2($id){


 $proyecto = Proyecto::find($id);
        $proyecto->nombre = Input::get("nombre");

         $fechainicio = Input::get("fechainicio");
	list($dia,$mes,$ano) = explode("/",$fechainicio);
	 $fechainicio = "$ano-$mes-$dia";

	  $fechatermino = Input::get("fechatermino");
	list($dia,$mes,$ano) = explode("/",$fechatermino);
	 $fechatermino = "$ano-$mes-$dia";


	 $proyecto->fechainicio = $fechainicio;
	$proyecto->fechatermino = $fechatermino;


        $proyecto->save();

        return Redirect::to("proyectos");

}

public function eliminar()
{
		$id = Input::get('id'); //acedemos a la variable id traida por AJAX ($.get)
        $proyecto = Proyecto::find($id);

        $proyecto->delete();
        //return $id;
}



}