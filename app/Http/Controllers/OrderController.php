<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $orders = Order::with(['items'])
                      ->where('user_id', $user->id)
                      ->orderBy('created_at', 'desc')
                      ->get();
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'billing_address' => 'nullable|string',
            'shipping_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Calculate totals
        $items = $request->items;
        $totalAmount = 0;
        $orderItems = [];

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            $subtotal = $product->price * $item['quantity'];
            $totalAmount += $subtotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
                'options' => $item['options'] ?? null,
            ];
        }

        // Add shipping cost (example calculation)
        $shippingCost = 10.00; // Fixed shipping cost for this example
        $totalAmount += $shippingCost;

        // Add tax (example calculation)
        $taxRate = 0.10; // 10% tax
        $taxAmount = $totalAmount * $taxRate;
        $totalAmount += $taxAmount;

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'tax_amount' => $taxAmount,
            'shipping_cost' => $shippingCost,
            'shipping_method' => $request->shipping_method,
            'shipping_address' => $request->shipping_address,
            'billing_address' => $request->billing_address ?? $request->shipping_address,
            'payment_status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return response()->json([
            'success' => true,
            'data' => $order->load('items'),
            'message' => 'Order created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $user = Auth::user();
        $order = Order::with(['items'])
                     ->where('user_id', $user->id)
                     ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        
        // Only allow updating certain fields
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:pending,processing,completed,declined,cancelled',
            'payment_status' => 'sometimes|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $order->update($request->only(['status', 'payment_status', 'notes']));

        return response()->json([
            'success' => true,
            'data' => $order->fresh()->load('items'),
            'message' => 'Order updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        
        // Only allow cancellation if order is pending
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending orders can be cancelled'
            ], 422);
        }
        
        $order->status = 'cancelled';
        $order->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully'
        ]);
    }
    
    /**
     * Admin method to list all orders
     */
    public function adminIndex(): JsonResponse
    {
        // Add authorization check here
        
        $orders = Order::with(['items', 'user'])
                      ->orderBy('created_at', 'desc')
                      ->get();
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
    
    /**
     * Admin method to update order status
     */
    public function adminUpdate(Request $request, string $id): JsonResponse
    {
        // Add authorization check here
        
        $order = Order::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,completed,declined,cancelled',
            'payment_status' => 'sometimes|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $order->update($request->only(['status', 'payment_status', 'notes']));

        return response()->json([
            'success' => true,
            'data' => $order->fresh()->load(['items', 'user']),
            'message' => 'Order updated successfully'
        ]);
    }
}
