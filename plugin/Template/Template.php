<?php

namespace Plugin\Template;

use Plugin\Core\Core;

/**
 * Name: Core.Template
 * Author: LyAPI
 * ModifyTime: 2019/07/29
 * Purpose: 插件开发模板
 */

class Template extends Core{

    //设置插件信息（请严格按照本模板编写代码）
    public function __construct(){
        $this->Plugin_Name = 'Template';
        $this->Plugin_Version = 'V1.0.1';
        $this->Plugin_Author = 'mrxiaozhuox';
        $this->Plugin_About = '这是一个插件开发模板';
        $this->Plugin_Examine = '';
    }

    // Your code should be written here ...

}