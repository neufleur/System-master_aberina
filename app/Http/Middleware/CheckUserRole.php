<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
//⓷　userがteacherの場合又はstudentだった場合についてコントローラーで値を返す
    public function handle($request, Closure $next, $role): Response
    // handle メソッドは、リクエストが特定の条件を満たしているかをチェックし、満たしていない場合には適切なレスポンス（エラーレスポンスなど）を返す　$nextはコントローラーに渡すために必要
    {
        $user = Auth::user();
           // メンテナンスモードチェックのロジック $role === 'teacher'要求された役割が teacher であることを確認
           //!$user->isTeacher() は、ユーザーが教師 (role 属性が 1, 2, 3 のいずれか) ではないことを確認します。
           if (!$user || ($role === 'teacher' && !$user->isTeacher()) || ($role === 'student' && !$user->isStudent())) {
             return new Response('Unauthorized', 403);
            }
            return $next($request);

    }
}

// || 2つの条件のうち、少なくとも1つが真（true）であれば、全体として真（true）を返す
// &&2つの条件がどちらも真（true）であれば、全体として真（true）を返します。どちらか一方でも偽（false）であれば、全体として偽（false）を返す
// === 同じ値かどうか確認
