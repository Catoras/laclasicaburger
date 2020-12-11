<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Gate;


class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware("es-admin");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::with("user")->paginate(10);
        return view("productos.index",compact("productos"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $producto=new Producto;
        $title=__("Crear producto");
        $textButton=__("Crear");
        $route=route("productos.store");
        return view("productos.create", compact("title","textButton","route","producto"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "nombre" => "required|max:50|unique:productos",
            "precio"=>"required",
            "tipo_de_producto"=>"required",
            "descripcion" => "required|string|min:10"
        ]);
        Producto::create($request->only("nombre", "precio", "tipo_de_producto","descripcion"));
        return redirect(route("productos.index"))
            ->with("success", __("¡Productos creado!"));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        $title=__("Mostrar producto");
        $textButton=__("Regresar");
        $route=route("productos.index");
        return view("productos.show", compact("title","textButton","route","producto"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        $update= true;
        $title=__("Editar producto");
        $textButton=__("Actualizar");
        $route=route("productos.update", ["producto"=>$producto]);
        return view("productos.create", compact("update","title","textButton","route","producto"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $this->validate($request, [
            "nombre" => "required|max:50|unique:productos,nombre," . $producto->id,
            "precio"=>"required",
            "tipo_de_producto"=>"required",
            "descripcion" => "required|string|min:10"
        ]);
        $producto->fill($request->only("nombre", "precio","tipo_de_producto","descripcion"))->save();
        return back()->with("success", __("¡Producto actualizado!"));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        if(Gate::allows('admin')){
            $producto->delete();
            return back()->with("success", __("¡Producto eliminado!"));
        }
        else{
            return back()->with("fail", __("Acceso Negado"));;
        }
    }
}
