<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel;
use App\Models\ProductVariantModel;
use App\Models\UserModel;
use App\Models\TokenModel;

class ProductApiController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $productModel  = new ProductModel();
        $variantModel  = new ProductVariantModel();

        $products = $productModel
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.is_active', 1)
            ->findAll();

        foreach ($products as &$product) {
            $product['variants'] = $variantModel
                ->where('product_id', $product['id'])
                ->findAll();
        }

        return $this->respond([
            'status'  => 'success',
            'data'    => $products,
            'message' => 'OK',
        ]);
    }

    public function show($id = null)
    {
        $productModel = new ProductModel();
        $variantModel = new ProductVariantModel();

        $product = $productModel
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.is_active', 1)
            ->find((int) $id);

        if (!$product) {
            return $this->failNotFound('Product not found.');
        }

        $product['variants'] = $variantModel->where('product_id', $id)->findAll();

        return $this->respond([
            'status'  => 'success',
            'data'    => $product,
            'message' => 'OK',
        ]);
    }

    public function stock($id = null)
    {
        $variantModel = new ProductVariantModel();
        $productModel = new ProductModel();

        $product = $productModel->where('is_active', 1)->find((int) $id);
        if (!$product) {
            return $this->failNotFound('Product not found.');
        }

        $variants = $variantModel
            ->select('id, sku, size, color, stock_quantity, price_modifier')
            ->where('product_id', (int) $id)
            ->findAll();

        return $this->respond([
            'status'  => 'success',
            'data'    => ['product_id' => (int) $id, 'stock' => $variants],
            'message' => 'OK',
        ]);
    }

    public function token()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (!$email || !$password) {
            return $this->failUnauthorized('Email and password are required.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->where('is_active', 1)->first();

        if (!$user || !password_verify((string) $password, $user['password_hash'])) {
            return $this->failUnauthorized('Invalid credentials.');
        }

        $tokenModel = new TokenModel();

        // Revoke existing tokens for this user
        $tokenModel->where('user_id', $user['id'])->delete();

        $rawToken  = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $tokenModel->insert([
            'user_id'    => $user['id'],
            'token'      => $rawToken,
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->respondCreated([
            'status'  => 'success',
            'data'    => [
                'token'      => $rawToken,
                'expires_at' => $expiresAt,
            ],
            'message' => 'Token generated.',
        ]);
    }
}
