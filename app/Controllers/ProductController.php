<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ProductVariantModel;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * ProductController
 *
 * CI4 Resource Controller handling all 7 RESTful methods for
 * product management: index, new, create, show, edit, update, delete.
 *
 * Member 3 — Inventory & Product Management
 */
class ProductController extends BaseController
{
    protected ProductModel        $productModel;
    protected ProductVariantModel $variantModel;
    protected CategoryModel       $categoryModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);

        $this->productModel  = new ProductModel();
        $this->variantModel  = new ProductVariantModel();
        $this->categoryModel = new CategoryModel();
    }

    // ─────────────────────────────────────────────────────────────────
    // index()  GET /products
    // List all active products with pagination (10/page) + 5-min cache.
    // ─────────────────────────────────────────────────────────────────
    public function index(): string
    {
        $q        = $this->request->getGet('q')        ?? '';
        $category = $this->request->getGet('category') ?? '';
        $stock    = $this->request->getGet('stock')    ?? '';

        // Build a unique cache key from the filter parameters + page number.
        $page      = (int) ($this->request->getGet('page') ?? 1);
        $cacheKey  = 'product_list_' . md5($q . $category . $stock . $page);

        // Try reading from cache (FileHandler, 5-minute TTL = 300 s).
        $cached = cache($cacheKey);
        if ($cached !== null) {
            return view('products/index', $cached);
        }

        // Build the query dynamically.
        $builder = $this->productModel
            ->select('products.*, categories.name AS category_name,
                      COALESCE(SUM(product_variants.stock_quantity), 0) AS total_stock')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->join('product_variants', 'product_variants.product_id = products.id', 'left')
            ->where('products.is_active', 1)
            ->groupBy('products.id');

        if ($q !== '') {
            $builder->groupStart()
                ->like('products.name', $q)
                ->orLike('products.brand', $q)
                ->groupEnd();
        }
        if ($category !== '') {
            $builder->where('products.category_id', $category);
        }
        if ($stock === 'low') {
            $builder->having('total_stock <', 10);
        } elseif ($stock === 'ok') {
            $builder->having('total_stock >=', 10);
        }

        $products = $builder->paginate(10);
        $pager         = $this->productModel->pager;
        $categories    = $this->categoryModel->findAll();
        $lowStockCount = $this->productModel
            ->select('products.id, COALESCE(SUM(product_variants.stock_quantity),0) AS ts')
            ->join('product_variants', 'product_variants.product_id = products.id', 'left')
            ->where('products.is_active', 1)
            ->groupBy('products.id')
            ->having('COALESCE(SUM(product_variants.stock_quantity), 0) <', 10)
            ->countAllResults();

        $data = compact('products', 'pager', 'categories', 'lowStockCount');

        // Store result in cache for 300 seconds (5 minutes).
        cache()->save($cacheKey, $data, 300);

        return view('products/index', $data);
    }

    // ─────────────────────────────────────────────────────────────────
    // new()  GET /products/new
    // Show the empty product creation form.
    // ─────────────────────────────────────────────────────────────────
    public function new(): string
    {
        $this->requireManagerOrAbove();

        return view('products/form', [
            'product'    => null,
            'variants'   => [],
            'categories' => $this->categoryModel->findAll(),
            'isEdit'     => false,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // create()  POST /products
    // Validate, upload image, resize to 800×800, thumbnail 200×200,
    // insert product + variants, clear cache.
    // ─────────────────────────────────────────────────────────────────
    public function create(): RedirectResponse|string
    {
        $this->requireManagerOrAbove();

        $rules = [
            'name'        => 'required|max_length[255]',
            'category_id' => 'required|is_not_unique[categories.id]',
            'brand'       => 'required|max_length[120]',
            'base_price'  => 'required|decimal|greater_than[0]',
            'description' => 'permit_empty|max_length[1000]',
            'image'       => 'permit_empty|is_image[image]|mime_in[image,image/jpeg,image/png,image/webp]|max_size[image,2048]',
        ];

        if (!$this->validate($rules)) {
            return view('products/form', [
                'product'    => null,
                'variants'   => $this->request->getPost('variants') ?? [],
                'categories' => $this->categoryModel->findAll(),
                'isEdit'     => false,
                'errors'     => $this->validator->getErrors(),
            ]);
        }

        $imagePath = $this->handleImageUpload();

        $productId = $this->productModel->insert([
            'category_id' => (int) $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description') ?? '',
            'brand'       => $this->request->getPost('brand'),
            'base_price'  => (float) $this->request->getPost('base_price'),
            'image_path'  => $imagePath,
            'is_active'   => 1,
        ]);

        $this->saveVariants((int) $productId);
        $this->clearProductCache();

        session()->setFlashdata('success', 'Product created successfully.');
        return redirect()->to(site_url('products/' . $productId));
    }

    // ─────────────────────────────────────────────────────────────────
    // show()  GET /products/{id}
    // Display a single product with all its variants.
    // ─────────────────────────────────────────────────────────────────
    public function show(int|string $id = null): string|RedirectResponse
    {
        $product = $this->productModel
            ->select('products.*, categories.name AS category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.id', $id)
            ->where('products.is_active', 1)
            ->first();

        if ($product === null) {
            session()->setFlashdata('error', 'Product not found.');
            return redirect()->to(site_url('products'));
        }

        $variants = $this->variantModel
            ->where('product_id', $id)
            ->orderBy('size')
            ->orderBy('color')
            ->findAll();

        return view('products/show', compact('product', 'variants'));
    }

    // ─────────────────────────────────────────────────────────────────
    // edit()  GET /products/{id}/edit
    // Show the pre-populated edit form.
    // ─────────────────────────────────────────────────────────────────
    public function edit(int|string $id = null): string|RedirectResponse
    {
        $this->requireManagerOrAbove();

        $product = $this->productModel->find($id);
        if ($product === null) {
            session()->setFlashdata('error', 'Product not found.');
            return redirect()->to(site_url('products'));
        }

        $variants = $this->variantModel
            ->where('product_id', $id)
            ->orderBy('size')->orderBy('color')
            ->findAll();

        return view('products/form', [
            'product'    => $product,
            'variants'   => $variants,
            'categories' => $this->categoryModel->findAll(),
            'isEdit'     => true,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // update()  PUT/PATCH /products/{id}
    // Validate, optionally replace image, update product + variants.
    // ─────────────────────────────────────────────────────────────────
    public function update(int|string $id = null): RedirectResponse|string
    {
        $this->requireManagerOrAbove();

        $product = $this->productModel->find($id);
        if ($product === null) {
            session()->setFlashdata('error', 'Product not found.');
            return redirect()->to(site_url('products'));
        }

        $rules = [
            'name'        => 'required|max_length[255]',
            'category_id' => 'required|is_not_unique[categories.id]',
            'brand'       => 'required|max_length[120]',
            'base_price'  => 'required|decimal|greater_than[0]',
            'description' => 'permit_empty|max_length[1000]',
            'image'       => 'permit_empty|is_image[image]|mime_in[image,image/jpeg,image/png,image/webp]|max_size[image,2048]',
        ];

        if (!$this->validate($rules)) {
            $variants = $this->variantModel->where('product_id', $id)->findAll();
            return view('products/form', [
                'product'    => $product,
                'variants'   => $variants,
                'categories' => $this->categoryModel->findAll(),
                'isEdit'     => true,
                'errors'     => $this->validator->getErrors(),
            ]);
        }

        $file = $this->request->getFile('image');
        $imagePath = ($file !== null && $file->isValid() && !$file->hasMoved())
            ? $this->handleImageUpload($product['image_path'])
            : $product['image_path'];

        $this->productModel->update($id, [
            'category_id' => (int) $this->request->getPost('category_id'),
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description') ?? '',
            'brand'       => $this->request->getPost('brand'),
            'base_price'  => (float) $this->request->getPost('base_price'),
            'image_path'  => $imagePath,
        ]);

        // Delete existing variants and re-insert the submitted ones.
        $this->variantModel->where('product_id', $id)->delete();
        $this->saveVariants((int) $id);
        $this->clearProductCache();

        session()->setFlashdata('success', 'Product updated successfully.');
        return redirect()->to(site_url('products/' . $id));
    }

    // ─────────────────────────────────────────────────────────────────
    // delete()  DELETE /products/{id}
    // Soft-delete: set is_active = 0. Clear cache afterwards.
    // ─────────────────────────────────────────────────────────────────
    public function delete(int|string $id = null): RedirectResponse
    {
        $this->requireManagerOrAbove();

        $product = $this->productModel->find($id);
        if ($product === null) {
            session()->setFlashdata('error', 'Product not found.');
            return redirect()->to(site_url('products'));
        }

        $this->productModel->update($id, ['is_active' => 0]);
        $this->clearProductCache();

        session()->setFlashdata('success', "\"" . esc($product['name']) . "\" has been deactivated.");
        return redirect()->to(site_url('products'));
    }

    // ─────────────────────────────────────────────────────────────────
    // adjustStock()  POST /products/{id}/stock
    // Manager/Staff can update the stock_quantity of a specific variant.
    // ─────────────────────────────────────────────────────────────────
    public function adjustStock(int|string $id = null): RedirectResponse
    {
        $role = session('role');
        if (!in_array($role, ['superadmin', 'manager', 'staff'], true)) {
            session()->setFlashdata('error', 'Access denied.');
            return redirect()->to(site_url('products'));
        }

        $rules = [
            'variant_id'    => 'required|is_not_unique[product_variants.id]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Invalid stock adjustment data.');
            return redirect()->to(site_url('products/' . $id));
        }

        $variantId = (int) $this->request->getPost('variant_id');
        $qty       = (int) $this->request->getPost('stock_quantity');

        $this->variantModel->update($variantId, ['stock_quantity' => $qty]);
        $this->clearProductCache();

        session()->setFlashdata('success', 'Stock updated successfully.');
        return redirect()->to(site_url('products/' . $id));
    }

    // ═════════════════════════════════════════════════════════════════
    // Private helpers
    // ═════════════════════════════════════════════════════════════════

    /**
     * Upload product image, resize to 800×800, create 200×200 thumbnail.
     * Returns the stored filename (relative to /uploads/products/).
     *
     * @param string|null $oldPath  Existing image path to delete on replacement.
     */
    private function handleImageUpload(?string $oldPath = null): string
    {
        $file = $this->request->getFile('image');

        if ($file === null || !$file->isValid() || $file->hasMoved()) {
            return $oldPath ?? '';
        }

        $uploadDir    = FCPATH . 'uploads/products/';
        $thumbDir     = FCPATH . 'uploads/products/thumbs/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        if (!is_dir($thumbDir)) {
            mkdir($thumbDir, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadDir, $newName);

        $fullPath  = $uploadDir . $newName;
        $thumbPath = $thumbDir  . $newName;

        // Resize main image to 800×800 (maintain aspect ratio).
        \Config\Services::image()
            ->withFile($fullPath)
            ->fit(800, 800, 'center')
            ->save($fullPath);

        // Create 200×200 thumbnail.
        \Config\Services::image()
            ->withFile($fullPath)
            ->fit(200, 200, 'center')
            ->save($thumbPath);

        // Remove old image + thumbnail if replacing.
        if ($oldPath !== null && $oldPath !== '') {
            @unlink($uploadDir . $oldPath);
            @unlink($thumbDir  . $oldPath);
        }

        return 'products/' . $newName;
    }

    /**
     * Insert submitted variant rows (from POST array "variants[]") for a product.
     */
    private function saveVariants(int $productId): void
    {
        $variants = $this->request->getPost('variants');
        if (empty($variants) || !is_array($variants)) {
            return;
        }

        $rows = [];
        foreach ($variants as $v) {
            if (empty($v['sku']) || empty($v['size']) || empty($v['color'])) {
                continue;
            }
            $rows[] = [
                'product_id'     => $productId,
                'size'           => trim($v['size']),
                'color'          => trim($v['color']),
                'sku'            => trim($v['sku']),
                'stock_quantity' => (int) ($v['stock_quantity'] ?? 0),
                'price_modifier' => (float) ($v['price_modifier'] ?? 0),
            ];
        }

        if (!empty($rows)) {
            $this->variantModel->insertBatch($rows);
        }
    }

    /**
     * Purge all product-list cache keys.
     * CI4 FileHandler stores keys with a predictable prefix — we delete by prefix.
     */
    private function clearProductCache(): void
    {
        cache()->clean();   // Clears entire file cache; swap for prefix-delete if using Redis.
    }

    /**
     * Redirect non-manager/non-superadmin users back to products listing.
     */
    private function requireManagerOrAbove(): void
    {
        $role = session('role');
        if (!in_array($role, ['superadmin', 'manager'], true)) {
            session()->setFlashdata('error', 'Access denied.');
            redirect()->to(site_url('products'))->send();
            exit;
        }
    }
}