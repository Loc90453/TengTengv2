<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string|null $NgayNhap
 * @property int|null $user_id
 * @property int|null $nhacungcap_id
 * @property int|null $LyDo
 * @property string|null $GhiChu
 * @property int|null $TrangThai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $kho_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, chitietphieunhap> $chitietphieunhap
 * @property-read int|null $chitietphieunhap_count
 * @property-read kho|null $kho
 * @property-read nhacungcap|null $nhacungcap
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap whereGhiChu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap whereKhoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap whereLyDo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap whereNgayNhap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap whereNhacungcapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|phieunhap whereUserId($value)
 *
 * @mixin \Eloquent
 */
class phieunhap extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $table = 'phieunhap';

    protected $fillable = [
        'id',
        'NgayNhap',
        'user_id',
        'giamsat_id',
        'nhacungcap_id',
        'kho_id',
        'LyDo',
        'GhiChu',
        'TrangThai',
    ];

    public function nhacungcap(): BelongsTo
    {
        return $this->belongsTo(nhacungcap::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function giamsat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'giamsat_id');
    }

    public function kho(): BelongsTo
    {
        return $this->belongsTo(kho::class);
    }

    public function chitietphieunhap(): HasMany
    {
        return $this->hasMany(chitietphieunhap::class);
    }
}
