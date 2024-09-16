<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    // This method will show products page
    public function index(Request $request){
        $search =$request['search'] ?? "";

        if($search != ""){
            $products = Product::where('name','LIKE',"%$search%")->orWhere('price','LIKE',"%$search%")->get();
        }else{
            $products = Product::orderBy('created_at','DESC')->get();
        }
        
        return view('products.list',[
        'products' => $products
        ]);
    }
   

   
    // this method will show create product page
    public function create(){
      return view('products.create');
    }
    
    // This method will store  a product in db
    public function store(Request $request){
        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:3',
            'price' => 'required|numeric'
        ];

        if($request->image !=""){
            $rules['image'] = 'image';
        }

       $validator =  Validator::make($request->all(),$rules);

       if($validator->fails()){
        return redirect()->route('products.create')->withInput()->withErrors($validator);
       }

       //here , we are inserting data in db
       $product = new Product();
       $product->name = $request->name; 
       $product->sku = $request->sku;
       $product->price = $request->price;
       $product->description = $request->description;
       $product->save();

       if($request->image !=""){
        // delete old image

        File::delete(public_path('upload/products/'.$product->image));
       //here we will save image
       $image = $request->image;
       $ext = $image->getClientOriginalExtension();
       $imageName = time().'.'.$ext;

       //save image to product directry
       $image->move(public_path('upload\products'),$imageName);

       // save image name in database
       $product->image = $imageName;
       $product->save();
       }

       return redirect()->route('products.index')->with('success','Products added Successfully.'); 
    }

    //This method will show edit product page
    public function edit($id){
    $product = Product::findOrFail($id);
     return view('products.edit',[
        'product' => $product
     ]);
    }
   
    // this method will update a product
    public function update($id, Request $request){
        $product = Product::findOrFail($id);

        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:3',
            'price' => 'required|numeric'
        ];

        if($request->image !=""){
            $rules['image'] = 'image';
        }

       $validator =  Validator::make($request->all(),$rules);

       if($validator->fails()){
        return redirect()->route('products.edit',$product->id)->withInput()->withErrors($validator);
       }

       //here , we are update data in db
       
       $product->name = $request->name; 
       $product->sku = $request->sku;
       $product->price = $request->price;
       $product->description = $request->description;
       $product->save();

       if($request->image !=""){
       //here we will save image
       $image = $request->image;
       $ext = $image->getClientOriginalExtension();
       $imageName = time().'.'.$ext;

       //save image to product directry
       $image->move(public_path('upload\products'),$imageName);

       // save image name in database
       $product->image = $imageName;
       $product->save();
       }

       return redirect()->route('products.index')->with('success','Products updated Successfully.'); 
    }
 
    // this method will delete the product
    public function destroy($id){
      $product = Product::findOrFail($id);

      File::delete(public_path('upload/products/'.$product->image));

      //delete product from database

      $product->delete();
      return redirect()->route('products.index')->with('success','Products deleted Successfully.');

    }

    public function export() 
    {
       // return Excel::download(new ProductsExport, 'products.xlsx');
       $timestamp = date('YmdHis'); // Get current timestamp in YYYYMMDDHHMMSS format
       $fileName = 'products_' . $timestamp . '.xlsx';
   
       return Excel::download(new ProductsExport, $fileName);
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv',
    ]);

    try {
        Excel::import(new ProductsImport, $request->file('file'));
        return back()->with('success', 'Products Imported Successfully');
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
        
        return back()->with('failures', $failures);
    }
}
}
