<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = sections::all();
        // $products = products::all();
        $products = products::with('section')->get();
        return view('products.products',compact('sections','products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
   $validated = $request->validate([
        'Product_name' => 'required|string|max:255|unique:products,Product_name',
        'description'  => 'required|string|max:1000',
        'section_id'   => 'required|integer|exists:sections,id',
    ], [
        'Product_name.required' => 'الرجاء ادخال الاسم',
        'Product_name.unique'   => 'الاسم مسجل مسبقا',
        'description.required'  => 'الرجاء ادخال الوصف',
        'section_id.required'   => 'الرجاء اختيار القسم',
    ]);

    products::create([
        'Product_name' => $validated['Product_name'],
        'section_id'   => $validated['section_id'],
        'description'  => $validated['description'],
        'created_by'   => Auth::user()->name,
    ]);

    session()->flash('Add', 'تمت إضافة المنتج بنجاح ✅');
    return redirect()->route('products.index');

}
    /**
     * Display the specified resource.
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, products $products)
    {
       $id = $request->id;

    $this->validate($request, [
        'Product_name' => 'required|max:255|unique:products,Product_name,' . $id,
        'description' => 'required',
        'section_id' => 'required|exists:sections,id',
    ], [
        'Product_name.required' => 'يرجى إدخال اسم المنتج',
        'Product_name.unique' => 'اسم المنتج مسجل مسبقًا',
        'description.required' => 'يرجى إدخال الملاحظات',
        'section_id.required' => 'يرجى اختيار القسم',
    ]);

    $product = products::findOrFail($id);

    $product->update([
        'Product_name' => $request->Product_name,
        'description' => $request->description,
        'section_id' => $request->section_id,
    ]);

    session()->flash('edit', 'تم تعديل المنتج بنجاح ✅');
    return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request )
    {
        $id = $request->id;
        products::find($id)->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return redirect('/products');
    }
}
