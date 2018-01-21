<?php

namespace App\Http\Controllers;

use App\Block;
use App\Handlers\RowHandler;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Добавление записи в БД
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addData(Request $request)
    {
        $handler = new RowHandler($request->input('data'));
        $handler->processing();
        return response()->json(['responseCode' => $handler->getResponseCode()], $handler->getResponseCode());
    }

    /**
     * Получение последних N блоков
     *
     * @param Request $request
     * @param int $count
     * @return \Illuminate\Http\JsonResponse
     */
    public function lastBlocks(Request $request, $count = 10)
    {
        return response()->json(Block::loadBlocks($count), 200);
    }
}
