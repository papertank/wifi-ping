<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    protected $table = 'reports';
    protected $fillable = ['closed', 'down', 'up'];
    protected $casts = ['closed' => 'boolean', 'down' => 'datetime', 'up' => 'datetime'];

    public function getMinutesAttribute()
    {
        if ( ! $this->down || ! $this->up ) {
            return null;
        }

        return $this->up->diffInMinutes($this->down);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'closed' => $this->closed,
            'down' => $this->down,
            'up' => $this->up,
            'minutes' => $this->minutes
        ];
    }

}
