<?php

declare(strict_types=1);

namespace Velor\Pages\Policies;

use App\Models\User;
use App\Models\Paragraph;
use App\Policies\Concerns\UsesRolePermissions;

class ParagraphPolicy
{
    use UsesRolePermissions;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, Paragraph::class, __FUNCTION__);
    }

    public function view(User $user, Paragraph $paragraph): bool
    {
        return $this->allows($user, Paragraph::class, __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, Paragraph::class, __FUNCTION__);
    }

    public function update(User $user, Paragraph $paragraph): bool
    {
        return $this->allows($user, Paragraph::class, __FUNCTION__);
    }

    public function delete(User $user, Paragraph $paragraph): bool
    {
        return $this->allows($user, Paragraph::class, __FUNCTION__);
    }

    public function restore(User $user, Paragraph $paragraph): bool
    {
        return $this->allows($user, Paragraph::class, __FUNCTION__);
    }

    public function forceDelete(User $user, Paragraph $paragraph): bool
    {
        return $this->allows($user, Paragraph::class, __FUNCTION__);
    }
}
