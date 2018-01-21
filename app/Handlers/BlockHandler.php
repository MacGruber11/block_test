<?php

namespace App\Handlers;


use App\Block;
use App\Row;

/**
 * Class BlockHandler
 * @package App\Handlers
 */
class BlockHandler
{
    /**
     * Кол-во записей, из которых формируется новый блок
     */
    const COUNT_ROWS = 5;

    /**
     * Записи для блока
     *
     * @var Row
     */
    private $_rows;

    /**
     * Текущий блок
     *
     * @var Block
     */
    private $_object;

    /**
     * Предыдущий блок
     *
     * @var Block
     */
    private $_previousBlock;

    /**
     * Блок-json
     *
     * @var array
     */
    private $_jsonData;

    /**
     * Предыдущий хеш
     *
     * @var string
     */
    private $_previousHash = '0';

    /**
     * BlockHandler constructor.
     * @param $rows
     */
    public function __construct($rows)
    {
        $this->_rows = $rows;
        $this->_object = new Block();
    }

    /**
     * Обработка данных
     *
     * @return $this
     */
    public function processing()
    {
        $this->_loadPreviousBlock()->_setPreviousHash()->_prepareJson()->_saveBlock();
        return $this;
    }

    /**
     * Получение предыдущего блока
     *
     * @return $this
     */
    private function _loadPreviousBlock()
    {
        $this->_previousBlock = Block::orderBy('id', 'desc')->first();
        return $this;
    }

    /**
     * @return $this
     */
    private function _setPreviousHash()
    {
        if (!is_null($this->_previousBlock)) {
            $this->_previousHash = $this->_previousBlock->hash;
        }
        return $this;
    }

    /**
     * Формирование блока-json
     *
     * @return $this
     */
    private function _prepareJson()
    {
        $this->_jsonData = [
            'previous_block_hash' => $this->_previousHash,
            'rows' => $this->_formatRows(),
            'timestamp' => time(),
        ];

        $this->_jsonData['block_hash'] = hash('sha256', json_encode($this->_jsonData));

        return $this;
    }

    /**
     * @return array
     */
    private function _formatRows()
    {
        $result = [];
        foreach ($this->_rows as $row) {
            $result[] = $row->data;
        }
        return $result;
    }

    /**
     * Сохранение блока в БД
     *
     * @return $this
     */
    private function _saveBlock()
    {
        $this->_object->hash = $this->_jsonData['block_hash'];
        $this->_object->block_data = serialize($this->_jsonData);
        if ($this->_object->save()) {
            foreach ($this->_rows as $row) {
                $row->block_id = $this->_object->id;
                $row->save();
            }
        }

        return $this;
    }

}
