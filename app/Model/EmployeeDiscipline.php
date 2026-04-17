<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;

class EmployeeDiscipline extends Model
{
    public $timestamps = false;
    protected $table = 'employee_disciplines';
    protected $fillable = ['employee_id', 'discipline_id'];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}