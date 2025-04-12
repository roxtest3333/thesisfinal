<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'file_id',
        'preferred_date',
        'preferred_time',
        'reason',
        'status',
        'semester_id',
        'school_year_id',  
        'remarks',
        'manual_school_year',  
        'manual_semester',  
        'copies',
        'reference_id',
        'completed_at',
    ];

    protected $casts = [
        'preferred_date' => 'date',  
        'created_at' => 'date',
        'completed_at' => 'date',
    ];

    public function student()
{
    return $this->belongsTo(\App\Models\Student::class, 'student_id');
}

    public function file()
    {
        return $this->belongsTo(File::class);
    }
    
public function schoolYear()
{
    return $this->belongsTo(SchoolYear::class, 'school_year_id');
}
public function semester()
{
    return $this->belongsTo(Semester::class);
}
public function followupRequest() {
    return $this->hasOne(Schedule::class, 'reference_id');
}

}
