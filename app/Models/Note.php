<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Note extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'notes';

    protected $primaryKey = 'id';

    //public $timestamps = false;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'status',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    public function publish(): bool
    {
        $this->status = 'published';
        return $this->save();
    }

    public function archive(): bool
    {
        $this->status = 'archived';
        return $this->save();
    }

    public static function searchPublished(string $q, int $limit = 20)
    {
        $q = trim($q);

        return static::query()
            ->where('status', 'published')
            ->where(function (Builder $x) use ($q) {
                $x->where('title', 'like', "%{$q}%")
                    ->orWhere('body', 'like', "%{$q}%");
            })
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get();
    }

    public static function pinNote(int $id) {
        $note = Note::query()->find($id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        };

        $note->update(['is_pinned' => true]);
        return response()->json(['message' => 'Note pinned'], 200);
    }

    public static function unpinNote(int $id) {
        $note = Note::query()->find($id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        };

        $note->update(['is_pinned' => false]);
        return response()->json(['message' => 'Note unpined'], 200);
    }

    public static function archiveNote(int $id) {
        $note = Note::query()->find($id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        };

        $note->update(['archive' => true]);
        return response()->json(['message' => 'Note archived'], 200);
    }

    public static function publishNote(int $id) {
        $note = Note::query()->find($id);
        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        };

        $note->update(['publish' => true]);
        return response()->json(['message' => 'Note published'], 200);
    }
}
