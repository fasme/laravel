<?php
class ControlgastoController extends BaseController {


public function mostrar(){

$controlgastos = Controlgasto::where("proyecto_id",'=',Session::get("proyecto")->id)->orderby("fecha","desc")->get();
        
        // Con el método all() le estamos pidiendo al modelo de Usuario
        // que busque todos los registros contenidos en esa tabla y los devuelva en un Array

/*
  $desde = Input::get("desde");
  $hasta = Input::get("hasta");



  if($desde && $hasta)
  {
    list($dia,$mes,$ano) = explode("/",$desde);
            $desde = "$ano-$mes-$dia";

            list($dia,$mes,$ano) = explode("/",$hasta);
            $hasta = "$ano-$mes-$dia";

    $controlgastos = $controlgastos->whereRaw("fecha BETWEEN '$desde' AND '$hasta'")->get();
  }
  else
  {
    $controlgastos = $controlgastos->take(10)->orderby("fecha","desc")->get();
  }
  */
        
        return View::make('controlgasto.lista', array('controlgastos' => $controlgastos));

}


public function nuevo()
{




    $controlgasto = new Controlgasto;
	$proyectos = Proyecto::all()->lists("nombre","id");
	$selected = array();

  $obras0 = array("0"=>"-- seleccione Obra --");
    $obras = Obra::Where("proyecto_id","=",Session::get("proyecto")->id)->lists("nombre","id");
 
    $obras = $obras0 + $obras;
 // array_unshift($obras, ' --- Seleccione una obra --- ');
    $selected2 = array();

    $partidas0 = array("0"=>"-- seleccione partida --");
    $partidas = Partida::Where("proyecto_id","=",Session::get("proyecto")->id)->lists("nombre","id");
   
   $partidas = $partidas0 + $partidas;

   
     $ggs0 = array("0"=>"-- seleccione categoria --");
    $ggs = Ggcategoria::where("proyecto_id","=",Session::get("proyecto")->id)->lists("nombre","id");

   
    $ggs = $ggs0 + $ggs;
//   array_unshift($ggs, ' --- Seleccione una partida --- ');


 //return View::make('controlgasto.crear', compact('proyectos','selected'), compact('obras','selected2'), compact('ggs','selected3'));
    return View::make('controlgasto.formulario')->with("controlgasto",$controlgasto)->with('proyectos',$proyectos)->with('obras',$obras)->with('ggs',$ggs)->with("partidas",$partidas);
}


public function nuevo2()
{

    $controlgasto = new Controlgasto;
    $data = Input::all();
  // return $data;
    

    if($controlgasto->isValid($data))
    {
         list($dia,$mes,$ano) = explode("/",$data['fecha']);
            $data['fecha'] = "$ano-$mes-$dia";
            
        $controlgasto->fill($data);

        $controlgasto->save();
         $lastid =  $controlgasto->id;

        

    if($data["concepto"] == "GG")
    {
        Controlgastogg::create(array("controlgasto_id"=>$lastid,"ggcategoria_id"=>$data["ggcategoria_id"]));
       // echo "gg";
    }
    else if($data["concepto"] == "CD")
    {
        Controlgastocd::create(array("controlgasto_id"=>$lastid,"partida_id"=>$data["partida_id"]));
      //  echo "cd";
    }



    // 1 Efectivo, 2 Cheque
    if($data["tipopago"] == "2")
    {
//echo "CHEQUE";
        $cheque = new Cheque;
        if($cheque->isValid($data))
        {
            list($dia,$mes,$ano) = explode("/",$data['fechapago']);
            $data['fechapago'] = "$ano-$mes-$dia";

             $data["controlgasto_id"] = $lastid;
            $cheque->fill($data);
            $cheque->save();
     // $cheque = Cheque::create($data);
        }
        else
        {
            return Redirect::to('controlgasto/nuevo')->withInput()->withErrors($cheque->errors);
        }
        
 
    }


        return Redirect::to('controlgasto/nuevo');
    }
     else
        {
            // En caso de error regresa a la acción create con los datos y los errores encontrados
return Redirect::to('controlgasto/nuevo')->withInput()->withErrors($controlgasto->errors);
            //return "mal2";
        }



            


    

    
   



}


public function editar($id)
{

    $controlgasto = Controlgasto::find($id);

    $proyectos = Proyecto::all()->lists("nombre","id");
    $selected = array();

    $obras0 = array("0"=>"-- Seleccione obra --");
    $obras = Obra::Where("proyecto_id","=",Session::get("proyecto")->id)->lists("nombre","id");
  //array_unshift($obras, ' --- Seleccione una obra --- ');
    $obras = $obras0 + $obras;
    $selected2 = array();

    $partidas = Partida::Where("proyecto_id","=",Session::get("proyecto")->id)->lists("nombre","id");
   // array_unshift($partidas, ' --- Seleccione una partida --- ');


    $ggs0 = array("0"=>"-- Seleccione categoria --");
    $ggs = Ggcategoria::where("proyecto_id","=",Session::get("proyecto")->id)->lists("nombre","id");


    $ggs = $ggs0 + $ggs;
//array_unshift($ggs, ' --- Seleccione una categoria --- ');
    $selected3 = array();

return View::make('controlgasto.formulario')->with("controlgasto",$controlgasto)->with('proyectos',$proyectos)->with('obras',$obras)->with('ggs',$ggs)->with("partidas",$partidas);


}

public function editar2($id)
{

    $controlgasto = Controlgasto::find($id);
    $data = Input::all();

    list($dia,$mes,$ano) = explode("/",$data['fecha']);
            $data['fecha'] = "$ano-$mes-$dia";
    
    $controlgasto->fill($data);
    $controlgasto->save();



     if($data["concepto"] == "GG")
    {
         $controlgastogg = Controlgasto::find($id)->controlgastogg()->first();
       
       $controlgastogg = Controlgastogg::find($controlgastogg->id);
       $controlgastogg->fill($data);
       $controlgastogg->save();
      
    }
    else if($data["concepto"] == "CD")
    {
        $controlgastocd = Controlgasto::find($id)->controlgastocd()->first();
        $controlgastocd = Controlgastocd::find($controlgastocd->id);
        $controlgastocd->fill($data);
        $controlgastocd->save();
     
    }

     if($data["tipopago"] == "2")
    {
        list($dia,$mes,$ano) = explode("/",$data['fechapago']);
        $data['fechapago'] = "$ano-$mes-$dia";

        $cheque_id = $controlgasto->cheque->id;

        $cheque = Cheque::find($cheque_id);

        $cheque->fill($data);
        $cheque->save();

 
 
    }

     return Redirect::to('controlgasto');



}


public function buscarcategoriasgg()
{
     $id = Input::get("option");
    $categoriagg = Ggcategoria::find($id);

   // Gastogeneral::
    return $categoriagg->gastogeneral->lists("nombre");
}


public function eliminar()
{
    $id = Input::get("id");
    $controlgasto = Controlgasto::find($id);
    $controlgasto->delete();
    return "o";

}



public function informecontabilidad(){



    return View::make("controlgasto.informecontabilidad");


}

public function informecontabilidad2(){

    $data = Input::all();

    //return $data["desde"];


    

    



    $rules = array(
           "periodomes"=>"required"
    );
 
$validator = Validator::make($data, $rules);


if ( $validator->fails() ){

    return Redirect::to('controlgasto/informecontabilidad')->withInput()->withErrors($validator->errors());
}
else
{
 /* 
     list($dia,$mes,$ano) = explode("/",$data['desde']);
     $data['desde'] = "$ano-$mes-$dia";

     list($dia,$mes,$ano) = explode("/",$data['hasta']);

     $data['hasta'] = "$ano-$mes-$dia";
     *

    $controlgastos = Controlgasto::whereBetween('fecha', array($data["desde"], $data["hasta"]))
    ->where('documento',"=",2)
    ->get();
     $html =  View::make("controlgasto.informecontabilidadpdf")->with("controlgastos",$controlgastos);

      return PDF::load($html, 'A4', 'portrait')->show();
      */


      $controlgastos = Controlgasto::where('periodomes',"=", $data["periodomes"])
      ->where("periodoano","=",$data["periodoano"])
    ->where('documento',"=",2)
    ->orwhere("documento","=",6)
    ->where("proyecto_id","=",Session::get("proyecto")->id)
    ->orderby("fecha")
    ->get();
     $html =  View::make("controlgasto.informecontabilidadpdf")->with("controlgastos",$controlgastos)->with("mes",$data["periodomes"])->with("ano",$data["periodoano"]);

      return PDF::load($html, 'A4', 'landscape')->show();

}
      
}   



public function informegeneralcontabilidad(){

return View::make("controlgasto.informegeneralcontabilidad");

}

public function informegeneralcontabilidad2(){


    $data = Input::all();

    //return $data["desde"];


    

    



    $rules = array(
           "periodomes"=>"required"
    );
 
$validator = Validator::make($data, $rules);


if ( $validator->fails() ){

    return Redirect::to('controlgasto/informecontabilidad')->withInput()->withErrors($validator->errors());
}
else
{

      $cantfacturas = Controlgasto::where('periodomes',"=", $data["periodomes"])
      ->where("periodoano","=",$data["periodoano"])
    ->where('documento',"=",2)
    ->orwhere("documento","=",6)
    ->where("proyecto_id","=",Session::get("proyecto")->id)
    ->orderby("fecha")
    ->count();


     $html =  View::make("controlgasto.informegeneralcontabilidadpdf")->with("mes",$data["periodomes"])->with("ano",$data["periodoano"])->with("retimpunico",$data["retimpunico"])->with("rettasas",$data["rettasas"]);


     //return $html;
    return PDF::load($html, 'A4', 'portrait')->show();

}

}




}

?>