<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ProductVariantModel;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ProductController extends ResourceController
{
    protected ProductModel $productModel;
    protected ProductVariantModel $variantModel;
    protected CategoryModel $categoryModel;
    protected string $format = 'json';

    public function __construct()
    {
        $this->productModel   = new ProductModel();
        $this->variantModel   = new ProductVariantModel();
        $this->categoryModel  = new CategoryModel();
    }

    /**
     * Display paginated product list with caching.
     */
    public function index(): ResponseInterface|string
    {
        // Role check: only manager, superadmin, or staff can view
        $role = session('role');
        if (!in_array($role, ['superadmin', 'manager', 'staff'], true)) {
            return redirect()->to('/login')->with('error', 'Unauthorized access.');
        }

        $cacheKey = 'product_list_page_' . ($this->request->getGet('page') ?? 1);

        $products = cache()->remember($cacheKey, 300, function () {
            return $this->productModel
                ->select('products.*, categories.name as category_name')
                ->join('categories', 'categories.id = products.category_id')
                ->where('products.is_active', true)
                ->paginate(10);
        });

        $data = [
            'products'   => $products,
            'pager'      => $this->productModel->pager,
            'categories' => $this->categoryModel->findAll(),
        ];

        return view('products/index', $data);
    }

    /**
     * Show form to create a new product.
     */
    public function new(): ResponseInterface|string
    {
        if (!in_array(session('role'), ['superadmin', 'manager'], true)) {
            return redirect()->back()->with('error', 'Only managers can add products.');
        }

        $data = [
            'categories' => $this->categoryModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('products/create', $data);
    }

    /**
     * Store new product with image upload and variants.
     */
    public function create(): ResponseInterface
    {
        if (!in_array(session('role'), ['superadmin', 'manager'], true)) {
            return $this->respond(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $rules = [
            'name'        => 'required|min_length[3]|max_length[255]',
            'category_id' => 'required|is_natural_no_zero',
            'brand'       => 'required|max_length[100]',
            'base_price'  => 'required|decimal|greater_than[0]',
            'description' => 'permit_empty|max_length[1000]',
            'image'       => 'permit_empty|is_image[image]|max_size[image,2048]|ext_in[image,jpg,png,webp]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $imagePath = null;
        $thumbPath = null;

        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $uploadPath = WRITEPATH . 'uploads/products/';
            $thumbPathDir = WRITEPATH . 'uploads/products/thumbs/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            if (!is_dir($thumbPathDir)) {
                mkdir($thumbPathDir, 0755, true);
            }

            $imageFile->move($uploadPath, $newName);
            $fullPath = $uploadPath . $newName;

            // Resize to 800x800
            \Config\Services::image()
                ->withFile($fullPath)
                ->resize(800, 800, true)
                ->save($uploadPath . 'resized_' . $newName);

            // Generate 200x200 thumbnail
            \Config\Services::image()
                ->withFile($fullPath)
                ->fit(200, 200)
                ->save($thumbPathDir . 'thumb_' . $newName);

            $imagePath = 'products/resized_' . $newName;
            $thumbPath = 'products/thumbs/thumb_' . $newName;
        }

        $productData = [
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'brand'       => $this->request->getPost('brand'),
            'base_price'  => $this->request->getPost('base_price'),
            'image_path'  => $imagePath,
            'is_active'   => true,
        ];

        $productId = $this->productModel->insert($productData, true);

        // Handle variants
        $variants = $this->request->getPost('variants');
        if (!empty($variants) && is_array($variants)) {
            foreach ($variants as $variant) {
                if (empty($variant['sku'])) {
                    continue;
                }

                $variantData = [
                    'product_id'      => $productId,
                    'size'            => $variant['size'] ?? 'M',
                    'color'           => $variant['color'] ?? 'Black',
                    'sku'             => strtoupper($variant['sku']),
                    'stock_quantity'  => (int) ($variant['stock'] ?? 0),
                    'price_modifier'  => (float) ($variant['price_modifier'] ?? 0),
                ];

                $this->variantModel->insert($variantData);
            }
        }

        // Clear cache on write
        cache()->delete('product_list_page_1');
        cache()->delete('product_list_page_2');

        return redirect()->to('/products')->with('success', 'Product created successfully.');
    }

    /**
     * Display single product with variants.
     */
    public function show($id = null): ResponseInterface|string
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $variants = $this->variantModel->where('product_id', $id)->findAll();

        $data = [
            'product'  => $product,
            'variants' => $variants,
        ];

        return view('products/show', $data);
    }

    /**
     * Show edit form.
     */
    public function edit($id = null): ResponseInterface|string
    {
        if (!in_array(session('role'), ['superadmin', 'manager'], true)) {
            return redirect()->back()->with('error', 'Only managers can edit products.');
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $data = [
            'product'    => $product,
            'variants'   => $this->variantModel->where('product_id', $id)->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('products/edit', $data);
    }

    /**
     * Update product and variants.
     */
    public function update($id = null): ResponseInterface
    {
        if (!in_array(session('role'), ['superadmin', 'manager'], true)) {
            return $this->respond(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            return $this->failNotFound('Product not found.');
        }

        $rules = [
            'name'        => 'required|min_length[3]|max_length[255]',
            'category_id' => 'required|is_natural_no_zero',
            'brand'       => 'required|max_length[100]',
            'base_price'  => 'required|decimal|greater_than[0]',
            'description' => 'permit_empty|max_length[1000]',
            'image'       => 'permit_empty|is_image[image]|max_size[image,2048]|ext_in[image,jpg,png,webp]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Handle new image upload if provided
        $imagePath = $product['image_path'];
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $uploadPath = WRITEPATH . 'uploads/products/';
            $thumbPathDir = WRITEPATH . 'uploads/products/thumbs/';

            $imageFile->move($uploadPath, $newName);
            $fullPath = $uploadPath . $newName;

            \Config\Services::image()
                ->withFile($fullPath)
                ->resize(800, 800, true)
                ->save($uploadPath . 'resized_' . $newName);

            \Config\Services::image()
                ->withFile($fullPath)
                ->fit(200, 200)
                ->save($thumbPathDir . 'thumb_' . $newName);

            $imagePath = 'products/resized_' . $newName;
        }

        $updateData = [
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'brand'       => $this->request->getPost('brand'),
            'base_price'  => $this->request->getPost('base_price'),
            'image_path'  => $imagePath,
        ];

        $this->productModel->update($id, $updateData);

        // Update existing variants and add new ones
        $variants = $this->request->getPost('variants');
        if (!empty($variants) && is_array($variants)) {
            foreach ($variants as $variantId => $variant) {
                $variantData = [
                    'size'           => $variant['size'] ?? 'M',
                    'color'          => $variant['color'] ?? 'Black',
                    'sku'            => strtoupper($variant['sku']),
                    'stock_quantity' => (int) ($variant['stock'] ?? 0),
                    'price_modifier' => (float) ($variant['price_modifier'] ?? 0),
                ];

                if (is_numeric($variantId) && $variantId > 0) {
                    $this->variantModel->update($variantId, $variantData);
                } else {
                    $variantData['product_id'] = $id;
                    $this->variantModel->insert($variantData);
                }
            }
        }

        // Clear cache
        cache()->delete('product_list_page_1');
        cache()->delete('product_list_page_2');

        return redirect()->to('/products')->with('success', 'Product updated successfully.');
    }

    /**
     * Soft delete product (set is_active = false).
     */
    public function delete($id = null): ResponseInterface
    {
        if (!in_array(session('role'), ['superadmin', 'manager'], true)) {
            return $this->respond(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $product = $this->productModel->find($id);
        if (!$product) {
            return $this->failNotFound('Product not found.');
        }

        $this->productModel->update($id, ['is_active' => false]);

        // Clear cache
        cache()->delete('product_list_page_1');
        cache()->delete('product_list_page_2');

        return redirect()->to('/products')->with('success', 'Product deleted successfully.');
    }

    /**
     * Stock adjustment for a specific variant (Manager/Staff).
     */
    public function adjustStock($variantId = null): ResponseInterface
    {
        $role = session('role');
        if (!in_array($role, ['superadmin', 'manager', 'staff'], true)) {
            return $this->respond(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $variant = $this->variantModel->find($variantId);
        if (!$variant) {
            return $this->failNotFound('Variant not found.');
        }

        $newStock = (int) $this->request->getPost('stock_quantity');
        if ($newStock < 0) {
            return $this->failValidationErrors(['stock_quantity' => 'Stock cannot be negative.']);
        }

        $this->variantModel->update($variantId, ['stock_quantity' => $newStock]);

        return redirect()->back()->with('success', 'Stock updated successfully.');
    }
}