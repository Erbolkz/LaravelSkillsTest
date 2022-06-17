@extends('layout')
@section('title') Products @endsection

@section('main_content')    

  <div id="msgToast"  class="toast align-items-center text-bg-success mb-3 w-100" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">        
      </div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>  

  @if($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form id="productForm" method="post" action="" >
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
    <button type="submit" title="Add product" class="btn btn-secondary p-2">Submit</button>
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
              <button type="submit" title="Edit product" class="btn btn-secondary editBtn" data="{{ $product->id}}">
                <i class="bi bi-pen"></i>
              </button>
              <button type="submit" title="Delete product" class="btn btn-secondary delBtn" data="{{ $product->id}}">
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
  

  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark">
        <div class="modal-header">
          <h5 class="modal-title" id="editModal">Edit product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editForm">
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

  <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmModal">Confirmation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure?</p>
          <form id="delForm">
            @csrf
            <input type="hidden" name="id" id="id" value="">
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">NO</button>
              <button type="submit" class="btn btn-primary">Yes</button>
            </div>
          </form>
        </div>        
      </div>
    </div>
  </div>

  <script>
    
    $(document).ready(function() {      
      const confirmForm = new bootstrap.Modal('#confirmModal', {})
      const editForm = new bootstrap.Modal('#editModal', {})

      async function refresh_table(){
        response = await fetch('/products/fetch')
        res = await response.json()
        $('tbody').append($('<tr><td><button class="delBtn">Click</button></td></tr>'))
        $("tbody").html('')
        $.each(res.products, function (index, value) {
          $('tbody').append($('<tr>')
            .append($('<td>').append(value.name))
            .append($('<td>').append(value.quantity))
            .append($('<td>').append(value.price))
            .append($('<td>').append(value.updated_at))
            .append($('<td>').append(value.total))          
            .append($('<td>')
              .append($('<button>')
                .attr('type', 'submit')     
                .attr('title', 'Edit product')                                               
                .attr('class', 'btn btn-secondary editBtn')                
                .attr('data',value.id)                
                .append($('<i>').attr('class','bi bi-pen')))
              .append($('<button>')
                .attr('type', 'submit')                  
                .attr('title', 'Delete product')                                  
                .attr('class', 'btn btn-secondary delBtn ms-1')                
                .attr('data', value.id)     
                .append($('<i>').attr('class','bi bi-trash3')))
            )                                                      
          )
        })   
        $('tbody').append($('<tr>')
          .append($('<th>').attr('colspan',4).append('Total'))
          .append($('<td>').append(res.sumTotal))      
          .append($('<td>'))                                                                                                                                          
        )
      }
      
      
      $("#productForm").on("submit", function(e){      
        e.preventDefault()      
        const toast = new bootstrap.Toast($('#msgToast'))    
        $.ajax({
          url: '/products/add',
          method: 'post',
          dataType: 'html',
          data: $(this).serialize(),
          success: function(response){
            data=JSON.parse(response)          
            if(data.status === true){
              $('#msgToast').attr('class','toast align-items-center text-bg-success mb-3 w-100')
              $(".toast-body").html(data.message)
              document.getElementById("productForm").reset()
              refresh_table()
              toast.show()                         
            }else{
              $('#msgToast').attr('class','toast align-items-center text-bg-danger mb-3 w-100')
              $(".toast-body").html("<ul></ul>")
              $.each(data.errors, function( index, value ) {
                $(".toast-body ul").append("<li>"+value+"</li>")              
              })
              toast.show()
            }                                                          
          },
          error:function () {
            $('#msgToast').attr('class','toast align-items-center text-bg-danger mb-3 w-100')
            $(".toast-body").html("Error: Sometong went wrong")         
            toast.show()  
          }
        })
      })
    

      $("#delForm").on("submit", function(e){      
        e.preventDefault()              
        confirmForm.hide()  
        const toast = new bootstrap.Toast($('#msgToast'))    
        $.ajax({
          url: '/products/del',
          method: 'post',
          dataType: 'html',
          data: $(this).serialize(),
          success: function(response){
            data=JSON.parse(response)          
            if(data.status === true){
              $('#msgToast').attr('class','toast align-items-center text-bg-success mb-3 w-100')
              $(".toast-body").html(data.message)            
              refresh_table()
              toast.show()                         
            }else{
              $('#msgToast').attr('class','toast align-items-center text-bg-danger mb-3 w-100')
              $(".toast-body").html("<ul></ul>")
              $.each(data.errors, function( index, value ) {
                $(".toast-body ul").append("<li>"+value+"</li>")              
              })
              toast.show()
            }                                                          
          },
          error:function () {
            $('#msgToast').attr('class','toast align-items-center text-bg-danger mb-3 w-100')
            $(".toast-body").html("Error: Sometong went wrong")         
            toast.show()  
          }
        })            
      })

      $("#editForm").on("submit", function(e){      
        e.preventDefault()                           
        editForm.hide()  
        const toast = new bootstrap.Toast($('#msgToast'))    
        $.ajax({
          url: '/products/edit',
          method: 'post',
          dataType: 'html',
          data: $(this).serialize(),
          success: function(response){
            data=JSON.parse(response)                     
            if(data.status === true){
              $('#msgToast').attr('class','toast align-items-center text-bg-success mb-3 w-100')
              $(".toast-body").html(data.message)            
              refresh_table()
              toast.show()                         
            }else{
              $('#msgToast').attr('class','toast align-items-center text-bg-danger mb-3 w-100')
              $(".toast-body").html("<ul></ul>")
              $.each(data.errors, function( index, value ) {
                $(".toast-body ul").append("<li>"+value+"</li>")              
              })
              toast.show()
            }                                                          
          },
          error:function () {
            $('#msgToast').attr('class','toast align-items-center text-bg-danger mb-3 w-100')
            $(".toast-body").html("Error: Sometong went wrong")         
            toast.show()  
          }
        })            
      })
  

      $(".delBtn").on("click",function(){        
        $("#delForm > input[name='id']").val($(this).attr("data"))  
        confirmForm.show()           
      })

      $(".editBtn").on("click", async function(){          
        const id = $(this).attr("data")
        response = await fetch('/products/' + id)
        product = await response.json()
        
        if(product.status == true){
          $("#editForm #id").val(id)
          $("#editForm #name").val(product.name)
          $("#editForm #quantity").val(product.quantity)
          $("#editForm #price").val(product.price)          
          editForm.show() 
        }              
      })        
    })
   
  </script>

@endsection