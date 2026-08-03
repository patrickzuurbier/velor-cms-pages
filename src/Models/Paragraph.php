<?php

declare(strict_types=1);

namespace Velor\Pages\Models;

use App\Models\AbstractModel;
use App\Contracts\Models\RowOrderableInterface;
use App\Concerns\Models\HasRowOrdering;
use App\Concerns\Models\UsesAudit;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Translatable\Translatable;
use App\Concerns\Models\SortTranslations;
use Spatie\Translatable\HasTranslations;
use App\Contracts\Models\TranslatableInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Velor\Pages\Database\Factories\ParagraphFactory;

/**
 * @mixin \Eloquent
 */
class Paragraph extends AbstractModel implements RowOrderableInterface, TranslatableInterface
{
    /** @use HasFactory<ParagraphFactory> */
    use HasFactory;
    use HasRowOrdering;
    use HasUuids;
    use HasTranslations;
    use Sortable;
    use SortTranslations;
    use UsesAudit;

    /**
     * @var array<int, string>
     */
    public array $translatable = [
        'title',
        'intro',
        'content',
        'anchor',
    ];

    /**
     * @var array<int, string>
     */
    public array $sortable = [
        'is_active',
        'name',
        'title',
        'intro',
        'content',
        'anchor',
        'sort_order',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<int, string>
     */
    protected array $audit = [
        'name',
        'title',
        'intro',
        'content',
        'anchor',
        'is_active',
        'sort_order',
        'page_id',
    ];

    /**
     * @var array<int, string>
     */
    protected array $rowOrderScopeColumns = [
        'page_id',
    ];

    protected $fillable = [
        'name',
        'title',
        'intro',
        'content',
        'anchor',
        'is_active',
        'sort_order',
        'page_id',
    ];

    /**
     * @return Factory<Paragraph>
     */
    protected static function newFactory(): Factory
    {
        return ParagraphFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'name'       => 'string',
            'title'      => Translatable::class,
            'intro'      => Translatable::class,
            'content'    => Translatable::class,
            'anchor'     => Translatable::class,
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Page, $this>
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * @param Builder<AbstractModel> $query
     * @param string $direction
     * @return Builder<AbstractModel>
     */
    public function titleSortable(Builder $query, string $direction): Builder
    {
        return $this->sortableTranslation($query, $direction, 'title');
    }

    /**
     * @param Builder<AbstractModel> $query
     * @param string $direction
     * @return Builder<AbstractModel>
     */
    public function introSortable(Builder $query, string $direction): Builder
    {
        return $this->sortableTranslation($query, $direction, 'intro');
    }

    /**
     * @param Builder<AbstractModel> $query
     * @param string $direction
     * @return Builder<AbstractModel>
     */
    public function contentSortable(Builder $query, string $direction): Builder
    {
        return $this->sortableTranslation($query, $direction, 'content');
    }

    /**
     * @param Builder<AbstractModel> $query
     * @param string $direction
     * @return Builder<AbstractModel>
     */
    public function anchorSortable(Builder $query, string $direction): Builder
    {
        return $this->sortableTranslation($query, $direction, 'anchor');
    }
}
