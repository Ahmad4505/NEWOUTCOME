<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;
use App\Models\invoices_details;
use App\Models\invoice_attachments;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        // $invoices=invoices::all();
          // جلب الفاتورة المحددة مع العلاقات (القسم والمنتج)
        $invoices = invoices::where('id',$id)->first();

            $details  = invoices_Details::where('id_Invoice',$id)->get();
        $attachments  = invoice_attachments::where('invoice_id',$id)->get();


        return view('invoices.invoices_details',compact('invoices','details','attachments'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $invoices = invoice_attachments::findOrFail($request->id_file);
        $invoices->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }

    public function open_file($invoice_number, $file_name)
    {

        $path = 'Attachments/' . $invoice_number . '/' . $file_name;
            if (!Storage::disk('public_uploads')->exists($invoice_number . '/' . $file_name)) {
                abort(404, 'الملف غير موجود');
            }

        return response()->file(public_path($path));
    }

    public function get_file($invoice_number, $file_name)
    {

        $path = 'Attachments/' . $invoice_number . '/' . $file_name;
            if (!Storage::disk('public_uploads')->exists($invoice_number . '/' . $file_name)) {
                abort(404, 'الملف غير موجود');
            }

        return response()->download(public_path($path));
    }
}
