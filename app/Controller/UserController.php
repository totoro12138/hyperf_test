<?php
declare(strict_types=1);
namespace App\Controller;

use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\ApiResponse;
use Hyperf\Apidog\Annotation\ApiVersion;
use Hyperf\Apidog\Annotation\Body;
use Hyperf\Apidog\Annotation\DeleteApi;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\Apidog\Annotation\Header;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\Query;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Apidog\Annotation\ApiDefinitions;
use Hyperf\Apidog\Annotation\ApiDefinition;

/**
 * @ApiVersion(version="v1")
 * @ApiController(tag="用户管理", description="用户的新增/修改/删除接口")
 * @ApiDefinitions({
 *  @ApiDefinition(name="UserOkResponse", properties={
 *     "code|响应码": 200,
 *     "msg|响应信息": "ok",
 *     "data|响应数据": {"$ref": "UserInfoData"}
 *  }),
 *  @ApiDefinition(name="UserInfoData", properties={
 *     "userInfo|用户数据": {"$ref": "UserInfoDetail"}
 *  }),
 *  @ApiDefinition(name="UserInfoDetail", properties={
 *     "id|用户ID": 1,
 *     "mobile|用户手机号": { "default": "13545321231", "type": "string" },
 *     "nickname|用户昵称": "nickname",
 *     "avatar": { "default": "avatar", "type": "string", "description": "用户头像" },
 *  })
 * })
 */
class UserController extends AbstractController
{
    /**
     * @PostApi(path="/user", description="添加一个用户")
     * @Header(key="token|接口访问凭证", rule="required")
     * @FormData(key="name|名称", rule="required|max:10|cb_checkName")
     * @FormData(key="sex|年龄", rule="integer|in:0,1")
     * @FormData(key="file|文件", rule="file")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="0", description="创建成功", schema={"id":1})
     */
    public function add()
    {
        return [
            'code' => 0,
            'id' => 1,
        ];
    }

    // 自定义的校验方法 rule 中 cb_*** 方式调用
    public function checkName($attribute, $value)
    {
        return true;
    }
    /**
     * 请注意 body 类型 rules 为数组类型
     * @DeleteApi(path="/user", description="删除用户")
     * @Body(rules={
     *     "id|用户id":"required|integer|max:10",
     *     "deepAssoc|深层关联":{
     *        "name_1|名称": "required|integer|max:20"
     *     },
     *     "deepUassoc|深层索引":{{
     *         "name_2|名称": "required|integer|max:20"
     *     }}
     * })
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="0", description="删除成功", schema={"id":1})
     */
    public function delete()
    {
        $request = ApplicationContext::getContainer()->get(RequestInterface::class);
        $body = $request->getBody()->getContents();
        return [
            'code' => 0,
            'query' => $request->getQueryParams(),
            'body' => json_decode($body, true),
        ];
    }

    /**
     * @GetApi(path="/user", description="获取用户详情")
     * @Query(key="id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="0", schema={"id":1,"name":"张三","age":1})
     */
    public function get()
    {
        return [
            'code' => 0,
            'id' => 1,
            'name' => '张三',
            'age' => 1,
        ];
    }

    /**
     * schema中可以指定$ref属性引用定义好的definition
     * @GetApi(path="/user/info", description="获取用户详情")
     * @Query(key="id", rule="required|integer|max:0")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="0", schema={"$ref": "UserOkResponse"})
     */
    public function info()
    {
        return [
            'code' => 0,
            'id' => 1,
            'name' => '张三',
            'age' => 1,
        ];
    }

    /**
     * @GetApi(path="/users", summary="用户列表")
     * @ApiResponse(code="200", description="ok", schema={{
     *     "a|aa": {{
     *          "a|aaa":"b","c|ccc":"d"
     *      }},
     *     "b|ids": {1,2,3},
     *     "c|strings": {"a","b","c"},
     *     "d|dd": {"a":"b","c":"d"},
     *     "e|ee": "f"
     * }})
     */
    public function list()
    {
        return [
            [
                "a" => [
                    ["a" => "b", "c" => "d"]
                ],
                "b" => [1, 2, 3],
                "c" => ["a", "b", "c"],
                "d" => [
                    "a" => "b",
                    "c" => "d",
                ],
                "e" => "f",
            ],
        ];
    }
}