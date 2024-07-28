@extends('admin.master')

@section('title')
    Cập nhật đơn hàng
@endsection

@section('content')
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    <form action="{{ route('orders.update', $order) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-4">
                <h2 class="mt-3 mb-3">Tổng tiền</h2>
                <h3 class="text-success">{{ number_format($order->total_amount) }}</h3>
                <ul>
                    <li>{{ $order->customer->name }}</li>
                    <li>{{ $order->customer->email }}</li>
                    <li>{{ $order->customer->address }}</li>
                    <li>{{ $order->customer->phone }}</li>
                </ul>
            </div>

            <div class="col-md-8">
                <h2 class="mt-3 mb-3">Danh sách sản phẩm</h2>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Qty (số lượng bán)</th>
                        </tr>
                        @foreach ($order->details as $detail)
                            <tr>
                                <td>
                                    {{ $detail->name }}
                                </td>
                                <td>
                                    {{ number_format($detail->price) }}
                                </td>
                                <td>
                                    <input type="hidden" name="order_details[{{ $detail->id }}][price]"
                                        value="{{ $detail->pivot->price }}">

                                    <input type="number" class="form-control"
                                        name="order_details[{{ $detail->id }}][qty]" value="{{ $detail->pivot->qty }}">
                                    @error("order_details.$detail->id.qty")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>

            <button type="submit" class="mt-3 btn btn-primary">Submit</button>
        </div>

    </form>
@endsection
