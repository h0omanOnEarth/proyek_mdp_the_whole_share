<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLoc extends Model
{
    use HasFactory;
    protected $table = "requests";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = ["location", "batch", "deadline", "note", "status"];
}
