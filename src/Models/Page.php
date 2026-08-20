<?php

declare(strict_types=1);

namespace Velor\Pages\Models;

use App\Models\AbstractModel;
use App\Concerns\Models\UsesAudit;
use Kyslik\ColumnSortable\Sortable;
use App\Concerns\Models\SortTranslations;
use Spatie\Translatable\Translatable;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use App\Contracts\Models\TranslatableInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Velor\Pages\Database\Factories\PageFactory;

/**
 * @mixin \Eloquent
 */
class Page extends AbstractModel implements TranslatableInterface
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;
    use HasUuids;
    use HasTranslations;
    use Sortable;
    use SortTranslations;
    use UsesAudit;

    protected $attributes = [
        'is_active' => false,
    ];

    /**
     * @var array<int, string>
     */
    public array $translatable = [
        'title',
        'slug',
        'intro',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * @var array<int, string>
     */
    public array $sortable = [
        'is_active',
        'name',
        'title',
        'slug',
        'intro',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<int, string>
     */
    protected array $audit = [
        'is_active',
        'name',
        'title',
        'slug',
        'intro',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $fillable = [
        'is_active',
        'name',
        'title',
        'slug',
        'intro',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * @return Factory<Page>
     */
    protected static function newFactory(): Factory
    {
        return PageFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_active'        => 'boolean',
            'title'            => Translatable::class,
            'slug'             => Translatable::class,
            'intro'            => Translatable::class,
            'meta_title'       => Translatable::class,
            'meta_description' => Translatable::class,
            'meta_keywords'    => Translatable::class,
            'created_at'       => 'datetime',
            'updated_at'       => 'datetime',
        ];
    }

    /**
     * @return HasMany<Paragraph, $this>
     */
    public function paragraphs(): HasMany
    {
        return $this->hasMany(Paragraph::class);
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
    public function slugSortable($query, $direction)
    {
        return $this->sortableTranslation($query, $direction, 'slug');
    }

    /**
     * @param Builder<AbstractModel> $query
     * @param string $direction
     * @return Builder<AbstractModel>
     */
    public function introSortable($query, $direction)
    {
        return $this->sortableTranslation($query, $direction, 'intro');
    }

    /**
     * @param Builder<AbstractModel> $query
     * @param string $direction
     * @return Builder<AbstractModel>
     */
    public function metaTitleSortable($query, $direction)
    {
        return $this->sortableTranslation($query, $direction, 'meta_title');
    }

    /**
     * @param Builder<AbstractModel> $query
     * @param string $direction
     * @return Builder<AbstractModel>
     */
    public function metaDescriptionSortable($query, $direction)
    {
        return $this->sortableTranslation($query, $direction, 'meta_description');
    }

    /**
     * @param Builder<AbstractModel> $query
     * @param string $direction
     * @return Builder<AbstractModel>
     */
    public function metaKeywordsSortable($query, $direction)
    {
        return $this->sortableTranslation($query, $direction, 'meta_keywords');
    }
}
