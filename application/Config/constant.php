<?php

/**
 * LyApi 常量定义文件
 */

namespace Application\Config;

// 接口常量
const API_STRUCTURE_INFO = [
    "structure" => [
        "code" => 200,
        "data" => [],
        "msg" => ""
    ],

    "http_code" => "code",
    "info_item" => "data",
    "error_msg" => "msg",

    // 基本结构修改符
    "struct_symbol" => "~",
    // 数据删除列表符
    "deltem_symbol" => "^"
];

// 日志常量
const LOG_LEVEL_INFORMATION = "info";
const LOG_LEVEL_WARNING = "warning";
const LOG_LEVEL_ERROR = "error";
const LOG_LEVEL_NOTICE = "notice";
const LOG_LEVEL_DEBUG = "debug";

// 状态码常量
const HTTP_CONTINUE = 100;
const HTTP_SWITCHING_PROTOCOLS = 101;
const HTTP_OK = 200;
const HTTP_CREATED = 201;
const HTTP_ACCEPTED = 202;
const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
const HTTP_NO_CONTENT = 204;
const HTTP_RESET_CONTENT = 205;
const HTTP_PARTIAL_CONTENT = 206;
const HTTP_BAD_REQUEST = 400;
const HTTP_UNAUTHORIZED = 401;
const HTTP_PAYMENT_REQUIRED = 402;
const HTTP_FORBIDDEN = 403;
const HTTP_NOT_FOUND = 404;
const HTTP_METHOD_NOT_ALLOWED = 405;
const HTTP_NOT_ACCEPTABLE = 406;
const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
const HTTP_REQUEST_TIME_OUT = 408;
const HTTP_CONFLICT = 409;
const HTTP_GONE = 410;
const HTTP_LENGTH_REQUIRED = 411;
const HTTP_PRECONDITION_FAILED = 412;
const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
const HTTP_REQUES_URI_TOO_LARGE = 414;
const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
const HTTP_EXPECTATION_FAILED = 417;
const HTTP_INTERNAL_SERVER_ERROR = 500;
const HTTP_NOT_IMPLEMENTED = 501;
const HTTP_BAD_GATEWAY = 502;
const HTTP_SERVICE_UNAVAILABLE = 503;
const HTTP_GATEWAY_TIME_OUT = 504;
