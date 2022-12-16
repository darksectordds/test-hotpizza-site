<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PiecemealListController extends Controller
{
    /**
     * @var Model $model
     */
    protected $model;

    /**
     * Название таблицы.
     *
     * @var string
     */
    protected $table;

    /**
     * Столбец, который используется
     * в фцнкциях: above,below
     *
     * @var string
     */
    protected $whereColumn = 'id';

    /**
     * Столбец сортировки.
     *
     * @var string
     */
    protected $orderByColumn = 'created_at';

    /**
     * Направление: убывание/возрастание.
     *
     * @var string
     */
    protected $orderByDirection = 'desc';

    /**
     * Ключ параметра GET-запроса под определения limit'a
     * вывода коллекции на странице.
     *
     * @var string
     */
    protected $requestKeyLimit = 'limit';

    /**
     * Ключ параметра GET-запроса под значение страницы.
     *
     * @var string
     */
    protected $requestKeyPage = 'page';

    /**
     * Ключ параметра GET-запроса под above,below,latest
     *
     * @var string
     */
    protected $requestKeyPoint = 'p';

    /**
     * Ключ параметра GET-запроса хранящий значение
     * под доп. where условие, дающая точку отчета
     * для above,below.
     *
     * @var string
     */
    protected $requestKeySplit = 'uid';

    /**
     * Ключ response коллекции данных.
     *
     * @var string
     */
    protected $responseKeyCollection = null;

    /**
     * Ключ response количества row с таким условием (без limit).
     *
     * @var string
     */
    protected $responseKeyCount = null;

    /**
     * PiecemealListController constructor.
     */
    public function __construct()
    {
        if (!isset($this->table)) {
            $tmp = new $this->model();
            $this->table = $tmp->getTable();
        }

        if (!isset($this->responseKeyCollection))
            $this->responseKeyCollection = $this->table;

        if (!isset($this->responseKeyCount))
            $this->responseKeyCount = $this->table.'__count';
    }

    /*
     |-------------------------------------------------------------------------------------
     | Query Builder
     |------------------------------------------------------------------------------
     */

    /**
     * Использование relationships в query-запросе.
     *
     * @return array
     */
    protected function withRelationships()
    {
        return [];
    }

    /**
     * Возвращает query builder'а модели
     *
     * @param Request $request
     * @param $nested_relationships
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Request $request, $nested_relationships = [])
    {
        if (count($nested_relationships) === 0) {
            $nested_relationships = self::withRelationships();
        }

        return $this->model::with($nested_relationships);
    }

    /**
     * Возвращает query builder'а модели выше выбранного
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function above(Request $request)
    {
        return $this->query($request)
            ->where($this->whereColumn, '>', $request->get($this->requestKeySplit))
            ->orderBy($this->orderByColumn, $this->orderByDirection);
    }

    /**
     * Возвращает query builder'а модели ниже выбранного
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function below(Request $request)
    {
        return $this->query($request)
            ->where($this->whereColumn, '<', $request->get($this->requestKeySplit))
            ->orderBy($this->orderByColumn, $this->orderByDirection);
    }

    /**
     * Возвращает query builder'а модели последних rows
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function latest(Request $request)
    {
        return $this->query($request)
            ->orderBy($this->orderByColumn, $this->orderByDirection)
            ->take(20)
            ->latest($this->orderByColumn);
    }

    /**
     *
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|null
     */
    public function order(Request $request)
    {
        $query = null;

        if ($request->has($this->requestKeyPoint)) {

            // подгрузка значений выше текущего
            if ($request->get($this->requestKeyPoint) === 'above') {
                $query = $this->above($request);
            }

            // подгрузка значений ниже текущего
            else if ($request->get($this->requestKeyPoint) === 'below') {
                $query = $this->below($request);
            }

            // подгрузка последних значений(первый запуск)
            else if ($request->get($this->requestKeyPoint) === 'latest') {
                $query = $this->latest($request);
            }
        }
        // обычная загрузка
        else {
            $query = $this->query($request);
        }

        return $query;
    }

    /**
     * Коллекция
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function collection(Request $request)
    {
        $query = $this->order($request);

        if ($request->has($this->requestKeyLimit)) {

            if ($request->has($this->requestKeyPage)) {
                $query->skip($request->get($this->requestKeyPage) * $request->get($this->requestKeyLimit));
            }

            $query->take($request->get($this->requestKeyLimit));
        }

        return $query->get();
    }

    /**
     * Количество rows по сформированному правилу модели.
     *
     * @param Request $request
     * @return int
     */
    public function count(Request $request)
    {
        return $this->order($request)->count();
    }

    /*
     |-------------------------------------------------------------------------------------
     | Основные
     |------------------------------------------------------------------------------
     */

    /**
     * Custom(own) правила валидации.
     *
     * @return array
     */
    protected function validateRulesCustom()
    {
        return [];
    }

    /**
     * Постраничные правила валидации.
     *
     * @return array
     */
    protected function validateRulesPagesExt()
    {
        return [
            $this->requestKeyLimit => 'required|numeric|in:20',
            $this->requestKeyPage => 'numeric|min:0',
        ];
    }

    /**
     * Правила валидации под above,below подгрузку.
     *
     * @return array
     */
    protected function validateRulesPiecemealListExt()
    {
        $table = $this->table;
        
        return [
            $this->requestKeyPoint => 'required|string|in:latest,above,below',
            $this->requestKeySplit => "required|exists:${$table},id",
        ];
    }

    /**
     * Объединение и определение массива правил валидации.
     *
     * @param Request $request
     * @return array
     */
    protected function validationRules(Request $request)
    {
        $rules = $this->validateRulesCustom();

        if ($request->has($this->requestKeySplit) && $request->has($this->requestKeyPoint)) {
            $rules = array_merge($rules, $this->validateRulesPiecemealListExt());
        }
        else {
            $rules = array_merge($rules, $this->validateRulesPagesExt());
        }

        return $rules;
    }

    /**
     * Валидация запроса.
     *
     * @param Request $request
     */
    public function validation(Request $request)
    {
        // получаем правила валидации данных
        $validation_array = $this->validationRules($request);

        // валидация данных
        $request->validate($validation_array);
    }

    /**
     * Основная функция получения списка добавляющая дополнительные фильтры.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // общая валидация
        $this->validation($request);

        // получение коллекции модели
        $res[$this->responseKeyCollection] = $this->collection($request);

        // получение количества модели(без limit)
        $res[$this->responseKeyCount] = $this->count($request);

        return response()->json($res);
    }
}
