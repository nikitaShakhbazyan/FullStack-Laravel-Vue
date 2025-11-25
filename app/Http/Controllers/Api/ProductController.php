<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $products = $this->productService->getProducts(
            $request->get('category_id'),
            $request->get('search')
        );

        return response()->json($products);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProduct($id);

        return response()->json($product);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());

        return response()->json($product, 201);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product = $this->productService->updateProduct($product, $request->validated());

        return response()->json($product);
    }

    public function destroy(Product $product): JsonResponse
    {
        if (!auth()->user()?->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->productService->deleteProduct($product);

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
