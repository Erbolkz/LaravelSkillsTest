@extends('layout')
@section('title') Products @endsection

@section('main_content')
  @if($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  <form method="post" action="/products/add">
    @csrf
    <div class="form-group mb-3">
      <label for="name">Product name</label>
      <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
    </div>
    <div class="form-group mb-3">
      <label for="quantity">Quantity in stock</label>
      <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity">
    </div>
    <div class="form-group mb-3">
      <label for="review">Price per item</label>
      <input type="text" class="form-control" id="price" name="price" placeholder="Enter price">
    </div>
    <button type="submit" class="btn btn-secondary p-2">Submit</button>
  </form>
  <div class="mt-3">
    <h1>All products</h1>
    <table class="table table-dark table-hover">
      <thead>
        <tr>
          <th scope="col">Product name</th>
          <th scope="col">Quantity in stock</th>
          <th scope="col">Price per item</th>
          <th scope="col">Datetime submitted</th>
          <th scope="col">Total value number</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>    
        @php
          $total = 0
        @endphp            
        @foreach($products as $product)   
          @php $total += $product->quantity * $product->price @endphp              
          <tr>
            <td scope="row">{{ $product->name }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->updated_at }}</td>
            <td>{{  $product->quantity * $product->price }}</td>
            <td>
              <button type="submit" class="btn btn-secondary" onclick="edit_product('{{ $product->id}}')">
                <i class="bi bi-pen"></i>
              </button>
              <button type="submit" class="btn btn-secondary" onclick="delete_product('{{ $product->id}}')">
                <i class="bi bi-trash3"></i>
              </button>
            </td>          
          </tr>   
        @endforeach                    
        <tr>
          <th scope="row"  colspan="4">Total</th>          
          <th>{{ $total }}</th>
          <td></td>
        </tr>
      </tbody>
    </table>    

   
  </div>
  <script>
    function delete_product(id) {     
      const formDelete = document.getElementById("formDelete") 
      formDelete.action = "/products/delete/" + id
      const confirmForm = new bootstrap.Modal('#confirmForm', {})
      confirmForm.show()      
    }

    async function edit_product(id) {
      response = await fetch('/products/' + id)
      product = await response.json()
      if(product.status == true){
        document.querySelector("#editForm form input[name='id']").value=id
        document.querySelector("#editForm form input[name='name']").value=product.name
        document.querySelector("#editForm form input[name='quantity']").value=product.quantity
        document.querySelector("#editForm form input[name='price']").value=product.price
        const editForm = new bootstrap.Modal('#editForm', {})
        editForm.show() 
      }         
    }
  </script>

  <div class="modal fade" id="editForm" tabindex="-1" aria-labelledby="editForm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark">
        <div class="modal-header">
          <h5 class="modal-title" id="editForm">Edit product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="/products/edit">
            @csrf
            <input type="hidden" name="id" id="id" value="">
            <div class="form-group mb-3">
              <label for="name">Product name</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
            </div>
            <div class="form-group mb-3">
              <label for="quantity">Quantity in stock</label>
              <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity">
            </div>
            <div class="form-group mb-3">
              <label for="review">Price per item</label>
              <input type="text" class="form-control" id="price" name="price" placeholder="Enter price">
            </div>
            <button type="submit" class="btn btn-secondary p-2">Submit</button>
          </form>                    
        </div>        
      </div>
    </div>
  </div>

  <div class="modal fade" id="confirmForm" tabindex="-1" aria-labelledby="confirmForm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmForm">Confirmation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure?</p>
          <form action="" id="formDelete">
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">NO</button>
              <button type="submit" class="btn btn-primary">Yes</button>
            </div>
          </form>
        </div>        
      </div>
    </div>
  </div>

@endsection