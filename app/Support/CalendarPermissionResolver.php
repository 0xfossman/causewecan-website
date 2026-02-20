<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class CalendarPermissionResolver
{
    public function canManage(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        if ($user->is_calendar_admin) {
            return true;
        }

        return DB::table('discord_user_roles as dur')
            ->join('discord_role_mappings as drm', 'dur.role_id', '=', 'drm.discord_role_id')
            ->where('dur.discord_user_id', $user->discord_id)
            ->where('drm.permission_key', 'calendar_manage')
            ->exists();
    }
}
