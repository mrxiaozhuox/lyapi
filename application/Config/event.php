<?php

/**
 * 事件监听器配置
 */

namespace Application\Config;

use LyApi\Support\Event;

/**
 * 事件监听器使用栈作为储存结构
 * 当多次监听同一个事件时，会从最后一个事件开始执行
 */

Event::on("event_name", function () {
    echo "do some thing ...";
    Event::interrupt(); // 阻断后续调用，将不会激活后续的同事件监听
});

// Event::trigger("event_name");
