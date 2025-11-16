<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\invoices;
use App\Models\sections;
use Illuminate\Http\Request;
use App\Events\MyEventClass;
use App\Models\invoices_details;
use Illuminate\Support\Facades\DB;
use App\Models\invoice_attachments;
use App\Models\products;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Add_invoice_new;
use Illuminate\Support\Facades\Notification;




class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices=invoices::all();
        return view('invoices.invoices',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = sections::all();
        return view('invoices.add_invoices',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // إنشاء الفاتورة
    $invoice = invoices::create([
        'invoice_number' => $request->invoice_number,
        'invoice_Date' => $request->invoice_Date,
        'Due_date' => $request->Due_date,
        'product' => $request->product,
        'section_id' => $request->Section,
        'Amount_collection' => $request->Amount_collection,
        'Amount_Commission' => $request->Amount_Commission,
        'Discount' => $request->Discount,
        'Value_VAT' => $request->Value_VAT,
        'Rate_VAT' => $request->Rate_VAT,
        'Total' => $request->Total,
        'Status' => 'غير مدفوعة',
        'Value_Status' => 2,
        'note' => $request->note,
    ]);

    $invoice_id = $invoice->id;

    // إنشاء تفاصيل الفاتورة
    invoices_details::create([
        'id_Invoice' => $invoice_id,
        'invoice_number' => $request->invoice_number,
        'product' => $request->product,
        'Section' => $request->Section,
        'Status' => 'غير مدفوعة',
        'Value_Status' => 2,
        'note' => $request->note,
        'user' => Auth::user()->name,
    ]);

    // حفظ المرفق إذا كان موجود
    if ($request->hasFile('pic')) {
        $image = $request->file('pic');
        $file_name = $image->getClientOriginalName();
        $invoice_number = $invoice->invoice_number;

        $attachments = new invoice_attachments();
        $attachments->file_name = $file_name;
        $attachments->invoice_number = $invoice_number;
        $attachments->Created_by = Auth::user()->name;
        $attachments->invoice_id = $invoice_id;
        $attachments->save();

        // نقل الملف للمجلد المطلوب
        $image->move(public_path('Attachments/' . $invoice_number), $file_name);
    }

    // // إرسال إشعارات للمستخدمين
    // $users = User::all();
    // Notification::send($users, new \App\Notifications\Add_invoice_new($invoice));

    // إطلاق الحدث
    // event(new MyEventClass('hello world'));

    // رسالة نجاح
    session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
    return back();
}


    /**
     * Display the specified resource.
     */
    public function show(invoices $invoices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = invoices::where('id', $id)->first();
        $sections = sections::all();
        return view('invoices.edit_invoice', compact('sections', 'invoices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices $invoices)
    {
        $invoices = invoices::findOrFail($request->invoice_id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoices $invoices)
    {
        //
    }
    public function getproducts($id){
        $products = DB::table('products')
        ->where('section_id', $id)
        ->pluck('Product_name', 'id');

    return response()->json($products);
    }
}
