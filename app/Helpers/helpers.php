<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('extract_youtube_video_id')) {
    /**
     * Extract YouTube video ID from various URL formats.
     * Supports: youtu.be/ID, youtube.com/watch?v=ID, youtube.com/embed/ID, or raw ID.
     *
     * @param string|null $input
     * @return string|null
     */
    function extract_youtube_video_id($input)
    {
        if (empty($input)) {
            return null;
        }
        $input = trim($input);
        // Already just a video ID (11 chars: A-Za-z0-9_-)
        if (preg_match('/^[\w-]{11}$/', $input)) {
            return $input;
        }
        // youtu.be/VIDEO_ID
        if (preg_match('/youtu\.be\/([\w-]{11})/', $input, $m)) {
            return $m[1];
        }
        // youtube.com/watch?v=VIDEO_ID or /embed/VIDEO_ID
        if (preg_match('/[?&]v=([\w-]{11})/', $input, $m)) {
            return $m[1];
        }
        if (preg_match('/youtube\.com\/embed\/([\w-]{11})/', $input, $m)) {
            return $m[1];
        }
        return $input;
    }
}

if (!function_exists('current_admin_user')) {
    /**
     * Get the currently authenticated admin or superadmin user.
     *
     * @return \App\Models\SuperAdmin|\App\Models\Admin|null
     */
    function current_admin_user()
    {
        if (Auth::guard('superadmin')->check()) {
            return Auth::guard('superadmin')->user();
        }
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        }
        return null;
    }
}

if (!function_exists('is_super_admin')) {
    /**
     * Check if the current user is a super admin.
     *
     * @return bool
     */
    function is_super_admin()
    {
        return Auth::guard('superadmin')->check();
    }
}

if (!function_exists('upload_url')) {
    /**
     * Build a public URL for an uploaded file.
     */
    function upload_url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        return '/uploads/'.ltrim($path, '/');
    }
}

if (!function_exists('with_upload_urls')) {
    /**
     * Append upload URLs to model attributes for API responses.
     */
    function with_upload_urls($model, array $fields): array
    {
        $data = $model instanceof \Illuminate\Database\Eloquent\Model
            ? $model->toArray()
            : (array) $model;

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $data[$field.'_url'] = upload_url($data[$field]);
            }
        }

        return $data;
    }
}

if (!function_exists('frontend_base_url')) {
    /**
     * Resolve the public website base URL (never the API host).
     */
    function frontend_base_url(): string
    {
        $candidates = [
            env('FRONTEND_URL'),
            config('app.frontend_url'),
            config('app.url'),
        ];

        $base = '';
        foreach ($candidates as $candidate) {
            $candidate = trim((string) $candidate);
            if ($candidate !== '') {
                $base = $candidate;
                break;
            }
        }

        if ($base === '') {
            $base = 'https://labaykph.com';
        }

        $parts = parse_url($base);
        $scheme = $parts['scheme'] ?? 'https';
        $host = $parts['host'] ?? '';

        // Email links must open the website, not api.labaykph.com / api.* hosts.
        if ($host !== '' && preg_match('/^api\./i', $host)) {
            $host = preg_replace('/^api\./i', '', $host);
        }

        if ($host === '') {
            $host = 'labaykph.com';
            $scheme = 'https';
        }

        // Prefer https for the live site.
        if (preg_match('/(^|\.)labaykph\.com$/i', $host)) {
            $scheme = 'https';
        }

        return rtrim($scheme.'://'.$host, '/');
    }
}

if (!function_exists('frontend_url')) {
    /**
     * Build an absolute URL on the public frontend (not the API host).
     */
    function frontend_url(string $path = ''): string
    {
        $base = frontend_base_url();
        $path = ltrim($path, '/');

        return $path === '' ? $base : $base.'/'.$path;
    }
}
