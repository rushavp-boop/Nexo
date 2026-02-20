<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'record_date',
        'notes',
        'prescription_file_path',
        'original_file_name',
        'mime_type',
        'file_size',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
