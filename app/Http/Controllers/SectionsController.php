<?php

namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = sections::all();
        return view('sections.sections',compact('sections'));
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
    // ✅ التحقق من صحة المدخلات
    $validated = $request->validate([
        'section_name' => 'required|string|max:255|unique:sections,section_name',
        'description'  => 'required|string|max:1000',
    ],[
        'section_name.required' => 'الرجاء ادخال الاسم',
        'section_name.unique' => 'الاسم مسجل مسبقا',
        'description.required'  => 'الرجاء ادخال الوصف',
    ]

);

    // ✅ إنشاء القسم
    sections::create([
        'section_name' => $validated['section_name'],
        'description'  => $validated['description'] ?? null,
        'created_by'   => Auth::user()->name,
    ]);

    // ✅ رسالة نجاح
    session()->flash('Add', 'تمت إضافة القسم بنجاح ✅');
    return redirect()->route('sections.index');
}


    public function show(sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sections $sections)
    {
         $id = $request->id;

        $this->validate($request, [

            'section_name' => 'required|max:255|unique:sections,section_name,'.$id,
            'description' => 'required',
        ],[

            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
            'description.required' =>'يرجي ادخال البيان',

        ]);

        $sections = sections::find($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('edit','تم تعديل القسم بنجاج');
        return redirect('/sections');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
         $id = $request->id;
        sections::find($id)->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}
