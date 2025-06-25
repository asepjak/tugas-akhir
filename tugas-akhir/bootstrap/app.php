<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->web(append: [
            // TAMBAHKAN SESSION MIDDLEWARE INI!
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\ShareFlashMessages::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Route middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'pimpinan' => \App\Http\Middleware\PimpinanMiddleware::class,
            'karyawan' => \App\Http\Middleware\KaryawanMiddleware::class,
            'attendance.check' => \App\Http\Middleware\AttendanceMiddleware::class,
        ]);

        // Throttle requests
        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handling untuk sistem absensi
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'error' => 'Silakan login terlebih dahulu.'
                ], 401);
            }

            return redirect()->guest(route('login'));
        });

        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden.',
                    'error' => 'Anda tidak memiliki akses untuk melakukan aksi ini.'
                ], 403);
            }

            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Not Found.',
                    'error' => 'Halaman yang Anda cari tidak ditemukan.'
                ], 404);
            }

            return response()->view('errors.404', [], 404);
        });
    })
    ->create();
