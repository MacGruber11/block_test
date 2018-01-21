<?php
/**
 * Created by PhpStorm.
 * User: Stepanov
 * Date: 21.01.2018
 * Time: 13:37
 */

namespace App\Handlers;


use App\Row;

/**
 * Class RowHandler
 * @package App\Handlers
 */
class RowHandler
{
    /**
     * Входящие данные
     *
     * @var string
     */
    private $_data;

    /**
     * Обьект новой записи в БД
     *
     * @var Row
     */
    private $_object;

    /**
     * Код ответа на запрос
     *
     * @var int
     */
    private $_responseCode = 422;

    /**
     * RowHandler constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->_data = $data;
        $this->_object = new Row();
    }

    /**
     * Обработка данных
     *
     * @return $this
     */
    public function processing()
    {
        if ($this->_dataIsValid()) {
            $this->_saveRow();
        }
        return $this;
    }

    /**
     * Код ответа
     *
     * @return int
     */
    public function getResponseCode()
    {
        return $this->_responseCode;
    }

    /**
     * Валидация входящих данных
     *
     * @return bool
     */
    private function _dataIsValid()
    {
        if (!is_null($this->_data) && strlen($this->_data)) {
            return true;
        }

        return false;
    }

    /**
     * Сохранение новой записи в БД
     *
     * @return $this
     */
    private function _saveRow()
    {
        $this->_object->data = $this->_data;
        if ($this->_object->save()) {
            $this->_responseCode = 200;
        }
        return $this;
    }


}

