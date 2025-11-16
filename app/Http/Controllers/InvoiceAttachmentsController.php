<?php

namespace App\Http\Controllers;

use App\Models\invoice_attachments;
use Illuminate\Http\Request;

class InvoiceAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'file_name' => 'required|mimes:pdf,jpeg,png,jpg|max:2048',
            'invoice_number' => 'required|string',
            'invoice_id' => 'required|integer'
        ], [
            'file_name.required' => 'يرجى اختيار ملف للتحميل.',
            'file_name.mimes' => 'يجب أن يكون الملف من نوع pdf أو jpeg أو png أو jpg.',
            'file_name.max' => 'حجم الملف يجب ألا يتجاوز 2 ميجابايت.',
        ]);

        $file = $request->file('file_name');
        $file_name = $file->getClientOriginalName();

        $invoice_folder = public_path('Attachments/' . $request->invoice_number);
        if (!file_exists($invoice_folder)) {
            mkdir($invoice_folder, 0777, true);
        }
        $file->move($invoice_folder, $file_name);
        $attachment = new \App\Models\invoice_attachments();
        $attachment->file_name = $file_name;
        $attachment->invoice_number = $request->invoice_number;
        $attachment->invoice_id = $request->invoice_id;
        $attachment->Created_by = auth()->user()->name;
        $attachment->save();
        session()->flash('Add', 'تم رفع المرفق بنجاح 🎉');

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoice_attachments $invoice_attachments)
    {
        //
    }
}
