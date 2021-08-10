<?php

declare(strict_types = 1);

namespace Lovetrytry\Jichukuangjia\Validation;

use \ArrayObject;
use Hyperf\Utils\Context;
use Lovetrytry\Jichukuangjia\Request;
use Lovetrytry\Jichukuangjia\Utils\Arr;
use Lovetrytry\Jichukuangjia\Mock\Handler as MockHandler;

/**
 * 接口响应验证
 *
 * @author     lovetrytry@yeah.net
 */
class InterfaceResponse
{
    /**
     * @var Lovetrytry\Jichukuangjia\Request
     */
    protected $request;

    /**
     * @var PsrResponseInterface@body@content to Array
     */
    protected $content;

    /**
     * { item_description }
     */
    protected $mockHandler;

    /**
     * @author     lovetrytry@yeah.net
     *
     * @param      array  $content  接口响应的数据，包含最外围，如 code，msg，data 等
     */
    public function __construct(array $content)
    {
        $this->content = $content;

        $this->request = new Request;

        $calledAction = $this->request->get("calledAction");

        $this->mockHandler = Context::set(
            MockHandler::class,
            new MockHandler(
                $calledAction["class"],
                $calledAction["action"]
            )
        );
    }

    public function handle()
    {
        $mockData = $this->getMockData();

        return $this->dataFormatValidation($mockData, $this->content);
    }

    public function getDefaultData($dataKey)
    {
        $mockData = $this->getMockData();

        if (is_array($mockData[$dataKey])) {
            if (Arr::isAssoc($mockData[$dataKey])) {
                return new ArrayObject;
            } else {
                return [];
            }
        } else {
            return "";
        }
    }

    protected function dataFormatValidation($formatData, $data)
    {
        $checkState = true;

        foreach ($formatData as $key => $value) {
            if (
                array_key_exists($key, $data)
                && gettype($value) === gettype($data[$key])
            ) {
                if (is_array($data[$key])) {
                    return $this->dataFormatValidation($value, $data[$key]);
                }

                continue;
            }

            $checkState = false;

            break;
        }

        return $checkState;
    }

    protected function getMockData()
    {
        if (! $this->mockHandler instanceof MockHandler) {
            $this->mockHandler = Context::get(MockHandler::class);
        }

        if (Context::has("mockData")) {
            return Context::get("mockData");
        }

        return Context::set("mockData", $this->mockHandler->getMockData());
    }
}
