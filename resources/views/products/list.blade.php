<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
    <div class="bg-dark py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <h3 class="text-white">Product Management System</h3>
          </div>
          <div class="col-md-4 d-flex justify-content-end align-items-center">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-primary">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row justify-content-center mt-4">
        <div class="col-md-10 d-flex justify-content-end">
          <a href="{{ route('products.create') }}" class="btn btn-dark">Create</a>
          <a href="{{ route('products.export') }}" class="btn btn-success ms-2">Export to Excel</a> <!-- Export Button -->
        </div>
        @php
          $searchValue = request('search', ''); 
        @endphp
        <div class="col-md-10 mt-3">
    <!-- Import Form -->
     <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="d-flex">
        @csrf
     <input type="file" name="file" class="form-control form-control-sm me-2" required>
     <button class="btn btn-primary btn-sm">Import Products</button>
   </form>
   </div>

        <div class="col-md-10 mt-3">
          <form action="" class="d-flex flex-column">
            <div class="form-group col-md-6 p-0">
              <input type="search" name="search" id="search" class="form-control" placeholder="Search By Product Name Or Product Price" value="{{ $searchValue }}">
            </div>
            <div class="d-flex mt-2">
              <button type="submit" class="btn btn-primary mr-2" style="margin-right:10px;">Search</button>
              <a href="{{ url('/products') }}" class="btn btn-secondary">Reset</a>
            </div>
          </form>
        </div>
      </div>

      <div class="row d-flex justify-content-center">
        @if (Session::has('success'))
          <div class="col-md-10 mt-4">
            <div class="alert alert-success">
              {{ Session::get('success') }}
            </div>
          </div>
        @endif

        @if (Session::has('failures'))
          <div class="col-md-10 mt-4">
            <div class="alert alert-danger">
              <ul>
                @foreach (session('failures') as $failure)
                  <li>Row {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        @endif
      </div>

      <div class="col-md-10">
        <div class="card border-0 shadow-lg my-4">
          <div class="card-header bg-dark">
            <h3 class="text-white">Products</h3>
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Image</th>
                  <th>Name</th>
                  <th>Sku</th>
                  <th>Price</th>
                  <th>Description</th>
                  <th>Created at</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @if($products->isNotEmpty())
                  @foreach ($products as $product)
                    <tr>
                      <td>{{ $product->id }}</td>
                      <td>
                        @if($product->image)
                          <img width="50" src="{{ asset('upload/products/'.$product->image) }}" alt="">
                        @endif
                      </td>
                      <td>{{ $product->name }}</td>
                      <td>{{ $product->sku }}</td>
                      <td>${{ $product->price }}</td>
                      <td>{{ $product->description }}</td>
                      <td>{{ \Carbon\Carbon::parse($product->created_at)->format('d M, Y') }}</td>
                      <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-dark">Edit</a>
                        <a href="#" onclick="deleteProduct({{ $product->id }})" class="btn btn-danger">Delete</a>
                        <form id="delete-product-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                          @csrf
                          @method('DELETE')
                        </form>
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
      function deleteProduct(id) {
        if (confirm("Are you sure you want to delete this product?")) {
          document.getElementById("delete-product-form-" + id).submit();
        }
      }
    </script>
  </body>
</html>
