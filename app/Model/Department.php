<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;

class Department extends Model{
    public $timestamps = false;
    protected $fillable = ['name'];
}