<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $timestamps = false;
    protected $fillable = ['last_name','first_name', 'middle_name', 'gender', 'birth_date', 'address', 'position', 'department_id'];

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function disciplines(){
        return $this->belongsToMany(Discipline::class,  'employee_disciplines', 'employee_id', 'discipline_id');
    }
}