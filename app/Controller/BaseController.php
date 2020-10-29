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
use App\Service\UserService;
use App\Constants\ErrorCode;

/**
 * @ApiVersion(version="v1")
 * @ApiController(tag="基础控制器", description="基础啊")
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
class BaseController extends AbstractController
{

    /**
     * 返回成功的请求
     *
     * @param array  $data
     * @param string $message
     *
     * @return array
     */
    public function success(array $data = [], $message = '操作成功')
    {
        $response = [
            'code' => 0,
            'message' => $message,
            'payload' => $data ?: (object)[],
        ];
//        Log::get('http.' . $this->getCalledSource())->info(0, $response);
        return $response;
    }

    /**
     * @param int         $code
     * @param string|null $message
     *
     * @return array
     */
    public function fail(int $code = -1, ?string $message = null)
    {
        $response = [
            'code' => $code,
            'message' => $message ?: ErrorCode::getMessage($code),
            'payload' => (object)[],
        ];
//        Log::get('http.' . $this->getCalledSource())->info($code, $response);
        return $response;
    }
    
}