<?php
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 * @mixin \Hyperf\Database\Model\Builder
 * @mixin \Hyperf\Database\Query\Builder
 * @method static where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static select($columns = ['*'])
 */
class BaseModel extends Model
{

    // const CREATED_AT = MODEL_CREATED_AT_FIELD;

    // const UPDATED_AT = MODEL_UPDATED_AT_FIELD;

    const STATUS_YES = 1;

    const STATUS_NOT = 0;

    public static $status = [
        self::STATUS_YES => '启用',
        self::STATUS_NOT => '禁用',
    ];

    /**
     * select options 通用搜索底层方法
     *
     * @param array          $attr
     * @param array          $extra_where
     * @param string         $name_key
     * @param string|integer $id_key
     * @param string         $logic
     * @param bool           $default_query
     *
     * @return array
     */
    public function search($attr, $extra_where = [], $name_key = 'name', $id_key = 'id', $logic = 'and', $default_query = false)
    {
        $where = [];
        $kw = request()->input('kw');
        if ($kw) {
            if (preg_match_all('/^\d+$/', $kw)) {
                $where[$id_key] = $kw;
            } elseif (preg_match_all('/^\d+?,/', $kw)) {
                $where[$id_key] = explode(',', $kw);
            } else {
                $where[$name_key] = ['like' => "%{$kw}%"];
            }
        }
        $id = request()->input('id');
        if ($id) {
            if (preg_match_all('/^\d+$/', $id)) {
                $where[$id_key] = $id;
            } elseif (preg_match_all('/^\d+?,/', $id)) {
                $where[$id_key] = explode(',', $id);
            }
        }
        if (!$default_query && !$where) {
            return [];
        }
        $where['__logic'] = $logic;
        $where = array_merge($where, $extra_where);
        $attr['limit'] = $attr['limit'] ?? 100;
        return $this->list($where, $attr)->toArray();
    }

    public function list($where, array $attr)
    {
        $query = $this->where2query($where);
        if (isset($attr['select'])) {
            $query = $query->select($attr['select']);
        }
        if (isset($attr['select_raw'])) {
            $query = $query->selectRaw($attr['select_raw']);
        }
        $order_by = $attr['order_by'] ?? '';
        if ($order_by) {
            $query = $query->orderByRaw($order_by);
        }
        if (isset($attr['limit'])) {
            $query = $query->limit($attr['limit']);
        }
        return $query->get();
    }

    public function getListByPage(int $page=1,array $column=['*'],int $per_page=15,string $page_name = 'page'){
        $obj = $this->paginate($per_page,$column,$page_name,$page);
        $obj = json_decode(json_encode($obj),true);
        return $obj;
    }
}
