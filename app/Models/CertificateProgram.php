<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateProgram extends Model
{
    protected $table = 'certificate_program'; // Ensure table name is correct
    protected $primaryKey = 'id'; // Define primary key if not 'id'
    public $timestamps = true; // Enable automatic timestamps (created_at, updated_at)
    protected $casts = [
        'final_question' => 'array',
    ];
    protected $fillable = [
        'title',
        'slug',
        'user_id',
        'status',
        'short_description',
        'description',
        'course_ids',
        'certificated_course_count',
        'final_pdf',
        'final_question',
        'logo',
        'thumbnail',
        'certificate_template',
        'created_at',
        'updated_at',
    ];
}
