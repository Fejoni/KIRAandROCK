<?php
/**
 * Created by VelgirLab
 */

namespace App\Models;

use App\Traits\OrderSort;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Track extends Model
{
    use OrderSort;

    const MAX_TAGS = 10;
    const MAX_GENRES = 10;
    const MAX_MOODS = 10;
    const MAX_USAGE_TYPE = 30;

    const COUNT_PAGINATE_PAGE = 30;

    const DEMO_TYPE = 'demo';
    const DOWNLOAD_TYPE = 'download';

    // Вычисление точного соответствия
    const EXACT_MATCH_RATION_STRICTLY = 0.5;
    const EXACT_MATCH_RATION_NOT_STRICTLY = 0.5;

    //Вычисление коэффициентов для тегов
    const TAG_MATCHING_COEFFICIENT = 0.13;
    const MAX_TAG_MATCHING_COEFFICIENT = 0.39;

    //Вычисление времени на сервисе по формуле
    const FORMULA_PERIOD = 90;
    const FORMULA_X = 0.3;

    //Вычисление рейтинга скачивания
    const MAX_DOWNLOAD_RATING_VALUE = 0.3;

protected $table = "tracks";

    protected $fillable = [
        "name", "description", "temp", "user_id", "downloaded_count", "listened_count", "favorites_count", "last_download_at", "order", "pick"
    ];

    public function image()
    {
        return $this->morphOne(Image::class,"object")->where("is_main",1);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->hasMany(File::class);
    }

    public function download()
    {
        return $this->belongsToMany(User::class, 'downloads');
    }

    public function downloads()
    {
        return $this->belongsToMany(Track::class, 'downloads');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tags_tracks');
    }

    public function moods()
    {
        return $this->belongsToMany(Mood::class, 'moods_tracks');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genres_tracks');
    }

    public function usage_types()
    {
        return $this->belongsToMany(UsageType::class, 'tracks_usage_types');
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
