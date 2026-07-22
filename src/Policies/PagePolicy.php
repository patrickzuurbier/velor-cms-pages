<?php

declare(strict_types=1);

namespace Velor\Pages\Policies;

use Velor\Pages\Models\Page;
use App\Models\User;
use App\Policies\Concerns\UsesRolePermissions;

class PagePolicy
{
    use UsesRolePermissions;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, Page::class, __FUNCTION__);
    }

    public function view(User $user, Page $page): bool
    {
        return $this->allows($user, Page::class, __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, Page::class, __FUNCTION__);
    }

    public function update(User $user, Page $page): bool
    {
        return $this->allows($user, Page::class, __FUNCTION__);
    }

    public function delete(User $user, Page $page): bool
    {
        return $this->allows($user, Page::class, __FUNCTION__);
    }

    public function restore(User $user, Page $page): bool
    {
        return $this->allows($user, Page::class, __FUNCTION__);
    }

    public function forceDelete(User $user, Page $page): bool
    {
        return $this->allows($user, Page::class, __FUNCTION__);
    }
}
