<?php

namespace App\Http\Controllers;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\DatabaseJson\Models\Products;

class MainController extends Controller
{
  public function home(){    
    return view('welcome');
  }  
  public function products(){    
    $products = Products::all();    
    return view('products',['products'=>$products]);
  }

  public function get_product($id){        
    $valid = filter_var($id, FILTER_VALIDATE_INT);
    if($valid){
      $products = Products::all();        
      if($products){
        $product = Products::find($id);
        if($product){          
          $res=[
            'status' => true,
            'name' => $product->name,
            'quantity' => $product->quantity,
            'price' => $product->price,            
            ];
          }else{
            $res=[
              'status' => false,
              'message' => 'Product not found',
            ];
          }                   
        }
    }else{
      $res=[
        'status' => false,
        'message' => 'Invalid id',
      ];
    }     
    return response()->json($res);  
  }

  public function product_add(Request $request){
    $valid = $request->validate([
      'name' => 'required|min:4|max:255',
      'quantity' => 'required|integer',
      'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
    ]);

    $products = new Products;
    $products->name = $request->input('name');
    $products->quantity = (int)$request->input('quantity');
    $products->price = (double)$request->input('price');
    $products->save();

    return redirect('/products')->with('success','Review Submitted');
  }

  public function product_edit(Request $request){    
    $valid = $request->validate([
      'id' => 'required|integer',
      'name' => 'required|min:4|max:255',
      'quantity' => 'required|integer',
      'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
    ]);
    
    $products = Products::update([
      'name' => $request->input('name'),
      'quantity' => (int) $request->input('quantity'),
      'price' => (double) $request->input('price'),
    ],(int)$request->input('id'));
      
    return redirect('/products')->with('success','Review Submitted');
  }

  public function product_delete($id){
    $valid = filter_var($id, FILTER_VALIDATE_INT);

    if($valid){
      $result = Products::find($id)->delete();   
      if($result){
        return redirect('/products')->with('success','Deleted');
      }else{
        return redirect('/products')->with('error','Error');  
      }         
    }else{
      return redirect('/products')->with('error','Invalid data');
    }         
  }
}
