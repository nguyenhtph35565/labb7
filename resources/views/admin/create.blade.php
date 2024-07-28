@extends('admin.master')

@section('title')
    CREATE ORDER
@endsection

@section('content')
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Supplier Section -->
            <div class="col-md-6">
                <h2 class="mt-3 mb-3">Supplier</h2>
                <div class="form-group mt-3">
                    <label for="supplier_name">Name</label>
                    <input type="text" name="supplier[name]" value="{{ old('supplier.name') }}" id="supplier_name"
                        class="form-control" placeholder="Enter supplier name">
                    @error('supplier.name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="supplier_address">Address</label>
                    <input type="text" name="supplier[address]" value="{{ old('supplier.address') }}"
                        id="supplier_address" class="form-control" placeholder="Enter supplier address">
                    @error('supplier.address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="supplier_phone">Phone</label>
                    <input type="tel" name="supplier[phone]" value="{{ old('supplier.phone') }}" id="supplier_phone"
                        class="form-control" placeholder="Enter supplier phone">
                    @error('supplier.phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="supplier_email">Email</label>
                    <input type="email" name="supplier[email]" value="{{ old('supplier.email') }}" id="supplier_email"
                        class="form-control" placeholder="Enter supplier email">
                    @error('supplier.email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Customer Section -->
            <div class="col-md-6">
                <h2 class="mt-3 mb-3">Customer</h2>
                <div class="form-group mt-3">
                    <label for="customer_name">Name</label>
                    <input type="text" name="customer[name]" value="{{ old('customer.name') }}" id="customer_name"
                        class="form-control" placeholder="Enter customer name">
                    @error('customer.name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="customer_address">Address</label>
                    <input type="text" name="customer[address]" value="{{ old('customer.address') }}"
                        id="customer_address" class="form-control" placeholder="Enter customer address">
                    @error('customer.address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="customer_phone">Phone</label>
                    <input type="tel" name="customer[phone]" value="{{ old('customer.phone') }}" id="customer_phone"
                        class="form-control" placeholder="Enter customer phone">
                    @error('customer.phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="customer_email">Email</label>
                    <input type="email" name="customer[email]" value="{{ old('customer.email') }}" id="customer_email"
                        class="form-control" placeholder="Enter customer email">
                    @error('customer.email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Product Section -->
            <div class="col-md-12">
                <h2 class="mt-3 mb-3">Product List</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Stock Qty</th>
                                <th>Qty (số lượng bán)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 2; $i++)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control"
                                            name="products[{{ $i }}][name]"
                                            value="{{ old("products.$i.name") }}" placeholder="Enter product name">
                                        @error("products.$i.name")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="file" class="form-control"
                                            name="products[{{ $i }}][image]">
                                        @error("products.$i.image")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"
                                            name="products[{{ $i }}][description]"
                                            value="{{ old("products.$i.description") }}"
                                            placeholder="Enter product description">
                                        @error("products.$i.description")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" class="form-control"
                                            name="products[{{ $i }}][price]"
                                            value="{{ old("products.$i.price") }}" placeholder="Enter product price">
                                        @error("products.$i.price")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" class="form-control"
                                            name="products[{{ $i }}][stock_qty]"
                                            value="{{ old("products.$i.stock_qty") }}"
                                            placeholder="Enter stock quantity">
                                        @error("products.$i.stock_qty")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" class="form-control"
                                            name="order_details[{{ $i }}][qty]"
                                            value="{{ old("order_details.$i.qty") }}" placeholder="Enter quantity sold">
                                        @error("order_details.$i.qty")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12 text-center mt-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
