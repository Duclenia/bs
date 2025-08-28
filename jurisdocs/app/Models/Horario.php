<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $fillable = [
        'day_of_week',
        'start_time',
        'end_time',
        'interval_minutes',
        'breaks',
        'day_off'
    ];

    protected $casts = [
        'breaks' => 'array'
    ];
    
    public function getDayOfWeekPtAttribute()
    {
        $dias = [
            'monday'    => 'Segunda-feira',
            'tuesday'   => 'Terça-feira',
            'wednesday' => 'Quarta-feira',
            'thursday'  => 'Quinta-feira',
            'friday'    => 'Sexta-feira',
            'saturday'  => 'Sábado',
            'sunday'    => 'Domingo',
        ];

        return $dias[$this->day_of_week] ?? $this->day_of_week;
    }
}
