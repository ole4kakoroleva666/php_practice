<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

    public function employees()
{
    return $this->belongsToMany(Employee::class, 'employee_disciplines', 'discipline_id', 'employee_id');
}
}