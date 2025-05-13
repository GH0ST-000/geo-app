<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UserStandard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'group_id',
        'slug',
        'file_name',
        'file_type',
        'file_path',
        'file_extension',
        'file_category',
        'reject_reason',
        'is_verified'
    ];

    /**
     * Get the user that owns the standard document.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get related files in the same group.
     */
    public function relatedFiles()
    {
        if (!$this->group_id) {
            return UserStandard::where('id', $this->id);
        }

        return UserStandard::where('group_id', $this->group_id);
    }

    /**
     * Get the full file URL.
     *
     * @return string|null
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }

        return null;
    }
}
