<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['customer', 'details'])->latest('id')->paginate(1);

        return view('admin.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $images = [];

        try {
            DB::transaction(function () use ($request, &$images) {
                $customer = Customer::create($request->customer);
                $supplier = Supplier::create($request->supplier);

                $orderDetails = [];
                $totalAmount = 0;
                foreach ($request->products as $key => $product) {
                    $product['supplier_id'] = $supplier->id;

                    if ($request->hasFile("products.$key.image")) {
                        $images[] = $product['image'] = Storage::put('products', $request->file("products.$key.image"));
                    }

                    $tmp = Product::query()->create($product);

                    $orderDetails[$tmp->id] = [
                        'qty' => $request->order_details[$key]['qty'],
                        'price' => $tmp->price
                    ];

                    $totalAmount += $request->order_details[$key]['qty'] * $tmp->price;
                }

                $order = Order::query()->create([
                    'customer_id' => $customer->id,
                    'total_amount' => $totalAmount,
                ]);

                $order->details()->attach($orderDetails);
            }, 3);

            return redirect()
                ->route('orders.index')
                ->with('success', 'Thao tác thành công!');
        } catch (Exception $exception) {

            foreach ($images as $image) {
                if (Storage::exists($image)) {
                    Storage::delete($image);
                }
            }

            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load(['customer', 'details']);

        return view('admin.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        try {
            DB::transaction(function () use ($order, $request) {
                $order->details()->sync($request->order_details);

                $orderDetail = array_map(function ($item) {
                    return $item['price'] * $item['qty'];
                }, $request->order_details);

                $totalAmount = array_sum($orderDetail);

                $order->update([
                    'total_amount' => $totalAmount
                ]);
            }, 3);

            return back()->with('success', 'Thao tác thành công!');
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {
                $order->details()->sync([]);

                $order->delete();
            }, 3);

            return back()->with('success', 'Thao tác thành công!');
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
// namespace App\Http\Controllers;

// use App\Models\Order;
// use App\Http\Requests\StoreOrderRequest;
// use App\Http\Requests\UpdateOrderRequest;
// use App\Models\Customer;
// use App\Models\Product;
// use App\Models\Supplier;
// use Exception;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Storage;

// class OrderController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      */
//     public function index()
//     {
//         $orders = Order::with(['customer', 'details'])->latest('id')->paginate(1);
//         return view('admin.index', compact('orders'));
//     }

//     /**
//      * Show the form for creating a new resource.
//      */
//     public function create()
//     {
//         return view('admin.create');
//     }

//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(StoreOrderRequest $request)
//     {
//         $images = [];
//         try {
//             DB::transaction(function () use ($request, &$images) {
//                 $customer = Customer::create($request->customer);
//                 $supplier = Supplier::create($request->supplier);

//                 $orderDetails = $this->processProducts($request->products, $supplier->id, $images, $request->order_details);
//                 $totalAmount = array_sum(array_column($orderDetails, 'total_price'));

//                 $order = Order::create([
//                     'customer_id' => $customer->id,
//                     'total_amount' => $totalAmount,
//                 ]);

//                 $order->details()->attach(array_column($orderDetails, 'details'));
//             }, 3);

//             return redirect()->route('orders.index')->with('success', 'Thao tác thành công!');
//         } catch (Exception $exception) {
//             $this->deleteImages($images);
//             return back()->with('error', $exception->getMessage());
//         }
//     }

//     /**
//      * Display the specified resource.
//      */
//     public function show(Order $order)
//     {
//         //
//     }

//     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit(Order $order)
//     {
//         $order->load(['customer', 'details']);
//         return view('admin.edit', compact('order'));
//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(UpdateOrderRequest $request, Order $order)
//     {
//         try {
//             DB::transaction(function () use ($order, $request) {
//                 $orderDetails = array_map(function ($detail) {
//                     return [
//                         'price' => $detail['price'],
//                         'qty' => $detail['qty'],
//                     ];
//                 }, $request->order_details);

//                 $totalAmount = array_sum(array_map(function ($detail) {
//                     return $detail['price'] * $detail['qty'];
//                 }, $orderDetails));

//                 $order->update(['total_amount' => $totalAmount]);
//                 $order->details()->sync($orderDetails);
//             }, 3);

//             return back()->with('success', 'Thao tác thành công!');
//         } catch (Exception $exception) {
//             return back()->with('error', $exception->getMessage());
//         }
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy(Order $order)
//     {
//         try {
//             DB::transaction(function () use ($order) {
//                 $order->details()->detach();
//                 $order->delete();
//             }, 3);

//             return back()->with('success', 'Thao tác thành công!');
//         } catch (Exception $exception) {
//             return back()->with('error', $exception->getMessage());
//         }
//     }

//     /**
//      * Process products and prepare order details.
//      */
//     private function processProducts(array $products, $supplierId, &$images, $orderDetails)
//     {
//         $processedDetails = [];

//         foreach ($products as $key => $product) {
//             $product['supplier_id'] = $supplierId;

//             if (request()->hasFile("products.$key.image")) {
//                 $images[] = $product['image'] = Storage::put('products', request()->file("products.$key.image"));
//             }

//             $createdProduct = Product::create($product);

//             $processedDetails[] = [
//                 'details' => [
//                     'product_id' => $createdProduct->id,
//                     'qty' => $orderDetails[$key]['qty'],
//                     'price' => $createdProduct->price,
//                 ],
//                 'total_price' => $orderDetails[$key]['qty'] * $createdProduct->price,
//             ];
//         }

//         return $processedDetails;
//     }

//     /**
//      * Delete images from storage.
//      */
//     private function deleteImages(array $images)
//     {
//         foreach ($images as $image) {
//             if (Storage::exists($image)) {
//                 Storage::delete($image);
//             }
//         }
//     }
// }
