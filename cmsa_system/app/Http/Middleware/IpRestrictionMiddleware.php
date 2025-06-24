<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IpRestrictionMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // 許可するIPアドレスのリスト
        $allowedIps = [
            '150.249.207.176',
        ];

        // リクエスト元のIPがリストに含まれていない場合、404エラーを返す
        if (!in_array($request->ip(), $allowedIps)) {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}
