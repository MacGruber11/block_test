<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $table = 'blocks';
    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * Получение последних N блоков
     *
     * @param $count
     * @return array
     */
    public static function loadBlocks($count)
    {
        $blocks = Block::orderBy('id', 'desc')->limit($count)->get();
        $result = ['count' => $blocks->count()];
        foreach ($blocks as $block) {
            $result['items'][] = unserialize($block->block_data);
        }
        return $result;
    }
}
