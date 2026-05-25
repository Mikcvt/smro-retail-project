<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TokenModel;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'data'    => null,
                    'message' => 'Unauthorized. Bearer token required.',
                ]);
        }

        $token = substr($authHeader, 7);

        $tokenModel = new TokenModel();
        $record = $tokenModel->where('token', $token)
                             ->where('expires_at >', date('Y-m-d H:i:s'))
                             ->first();

        if (!$record) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'data'    => null,
                    'message' => 'Unauthorized. Invalid or expired token.',
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
