<?php

namespace App\Support;

use App\Models\Admin;
use App\Models\SuperAdmin;

class AdminAccess
{
    public const ROLE_SUPERADMIN = 'superadmin';

    public const ROLE_ADMIN = 'admin';

    public const ROLE_STAFF = 'staff';

    /** Resources staff may list/view. */
    private const STAFF_READ_RESOURCES = [
        'tours',
        'inquiries',
        'users',
        'subscribers',
        'messages',
    ];

    /** Resources staff may delete (view + clear inbox). */
    private const STAFF_DESTROY_RESOURCES = [
        'inquiries',
    ];

    public static function role(mixed $user): string
    {
        if ($user instanceof SuperAdmin) {
            return self::ROLE_SUPERADMIN;
        }

        if ($user instanceof Admin) {
            $role = (string) ($user->role ?: self::ROLE_ADMIN);

            return in_array($role, [self::ROLE_ADMIN, self::ROLE_STAFF], true)
                ? $role
                : self::ROLE_ADMIN;
        }

        return self::ROLE_ADMIN;
    }

    public static function isStaff(mixed $user): bool
    {
        return self::role($user) === self::ROLE_STAFF;
    }

    public static function isSuperAdmin(mixed $user): bool
    {
        return self::role($user) === self::ROLE_SUPERADMIN;
    }

    /**
     * @param  'index'|'show'|'store'|'update'|'destroy'  $action
     */
    public static function canResource(mixed $user, string $resource, string $action): bool
    {
        $role = self::role($user);

        if ($role === self::ROLE_SUPERADMIN || $role === self::ROLE_ADMIN) {
            return true;
        }

        if ($role !== self::ROLE_STAFF) {
            return false;
        }

        if (in_array($action, ['index', 'show'], true)) {
            return in_array($resource, self::STAFF_READ_RESOURCES, true);
        }

        if ($action === 'destroy') {
            return in_array($resource, self::STAFF_DESTROY_RESOURCES, true);
        }

        return false;
    }

    public static function canApprovePayments(mixed $user): bool
    {
        return in_array(self::role($user), [
            self::ROLE_SUPERADMIN,
            self::ROLE_ADMIN,
            self::ROLE_STAFF,
        ], true);
    }

    public static function canReplyMessages(mixed $user): bool
    {
        return in_array(self::role($user), [
            self::ROLE_SUPERADMIN,
            self::ROLE_ADMIN,
            self::ROLE_STAFF,
        ], true);
    }

    public static function canManageSubscribers(mixed $user): bool
    {
        return ! self::isStaff($user);
    }

    public static function denyUnless(bool $allowed, string $message = 'You do not have permission to perform this action.'): void
    {
        if (! $allowed) {
            abort(403, $message);
        }
    }
}
