<?php

declare(strict_types=1);

namespace Velor\Pages\Enums;

use App\Concerns\Enums\EnumHelper;
use App\Contracts\Enums\HasEnumHelperInterface;

enum ParagraphImagePositionEnum: string implements HasEnumHelperInterface
{
    use EnumHelper;

    case ABOVE = 'above';
    case LEFT = 'left';
    case RIGHT = 'right';

    /**
     * @return array<int, ParagraphImagePositionEnum>
     */
    public static function enum(): array
    {
        return self::cases();
    }

    public function labelTranslationKey(): string
    {
        return 'velor-pages::resources.paragraphs.image_positions.' . $this->value;
    }
}
