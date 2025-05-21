<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

  

    protected $fillable = [
        'title',
        'description',
        'price',
        'category',
        'user_id',
        'start_date',
        'end_date',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }

// app/Models/Course.php

public function materials()
{
    return $this->hasMany(CourseMaterial::class);
}




}
