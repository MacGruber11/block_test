<?php

namespace App;

use App\Handlers\BlockHandler;
use Illuminate\Database\Eloquent\Model;

class Row extends Model
{
    protected $table = 'rows';
    protected $primaryKey = 'id';
    public $timestamps = true;


    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $withoutBlockRows = Row::where('block_id',0)->get();
            if($withoutBlockRows->count() % BlockHandler::COUNT_ROWS == 0){
                $handler = new BlockHandler($withoutBlockRows);
                $handler->processing();
            }
        });
    }

}
