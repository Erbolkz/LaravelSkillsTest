<?php

namespace App\Http\Controllers;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\DatabaseJson\Models\Products;
use Illuminate\Support\Facades\Validator;

class MainController extends Controller
{
  public function home(){    
    return view('welcome');
  }  
  public function products(){    
    $products = Products::all();    
    return view('products',['products'=>$products]);
  }

  public function products_fetch(){    
    $products = Products::all();     
    $res = ['products'=>[], 'sumTotal'=>0];
    $sumTotal= 0;      
    foreach($products as $product){
      $sumTotal += $product->quantity * $product->price;
      $res['products'][]=['id'=>$product->id,'name'=>$product->name,'quantity'=>$product->quantity,'price'=>$product->price,'updated_at'=>$product->updated_at,'total'=>$product->quantity*$product->price];
    }
    $res['sumTotal']=$sumTotal;    
    return response()->json($res);
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
    $validator = Validator::make($request->all(), [
      'name' => 'required|min:4|max:255',
      'quantity' => 'required|integer',
      'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
    ]);
  
    if ($validator->fails()) {
      return response()->json(['status' => false, 'errors'=>$validator->errors()->all()]);     
    }
        
    $products = new Products;
    $products->name = $request->input('name');
    $products->quantity = (int)$request->input('quantity');
    $products->price = (double)$request->input('price');
    if($products->save()){
      $res=[
        'status' => true,
        'message' =>  $request->input('name') . ' added successfully',        
      ];      
    }else{
      $res=[
        'status' => false,
        'message' => $request->input('name') .  ' was not added',
      ];
    }    
    
    return response()->json($res);      
  }

  public function product_edit(Request $request){    
    $validator = Validator::make($request->all(), [
      'id' => 'required|integer',
      'name' => 'required|min:4|max:255',
      'quantity' => 'required|integer',
      'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
    ]);
  
    if ($validator->fails()) {
      return response()->json(['status' => false, 'errors'=>$validator->errors()->all()]);     
    }
    
    $result = Products::update([
      'name' => $request->input('name'),
      'quantity' => (int) $request->input('quantity'),
      'price' => (double) $request->input('price'),
    ],(int)$request->input('id'));

    if($result){
      $res=[
        'status' => true,
        'message' => 'Product Edited successfully',        
      ];           
    }else{
      $res=[
        'status' => false,
        'message' => 'Error',        
      ];   
    }    
    return response()->json($res);          
  }

  public function product_del(Request $request){    
    $validator = Validator::make($request->all(), [
      'id' => 'required|integer',      
    ]);
  
    if ($validator->fails()) {
      return response()->json(['status' => false, 'errors'=>$validator->errors()->all()]);     
    }

    $result = Products::find($request->input('id'))->delete();   
    if($result){
      $res=[
        'status' => true,
        'message' => 'Product deleted',        
      ];           
    }else{
      $res=[
        'status' => false,
        'message' => 'Error',        
      ];   
    }    
    return response()->json($res);
  }
}
