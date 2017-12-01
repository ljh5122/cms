<?php

namespace lib;
use \lib\Config;

/**
* 控制器
*/
class Controller {

	public $assign = null;

	protected function fetch($templateFile = '', $content = ''){
        $templateFile   =   $this->parseTemplate($templateFile);
        
		// 页面缓存
        ob_start();
        ob_implicit_flush(0);

        // 模板阵列变量分解成为独立变量
        extract($this->assign, EXTR_OVERWRITE);

        // 直接载入PHP模板
        empty($content) ? include $templateFile : eval('?>'.$content);

        // 获取并清空缓存
        $content = ob_get_clean();

        // 输出模板文件
        $this->render($content);
	}

	/**
     * 自动定位模板文件
     * @access protected
     * @param string $template 模板文件规则
     * @return string
     */
    private function parseTemplate($template='') {
        if(is_file($template)) {
            return $template;
        }

        // 分析模板文件规则
        if('' == $template) {
            // 如果模板文件名为空 按照默认规则定位
            $template = CONTROLLER_NAME . '/' . ACTION_NAME;
        }elseif(false === strpos($template, '/')){
            $template = CONTROLLER_NAME . '/' . $template;
        }

        $file   =   APP_PATH.'/view/'.$template.Config::get('template_suffix');
        if(!is_file($file)){
        	exit('模板文件不存在 :'.$template);
        }

        return $file;
    }

    /**
     * 输出内容文本可以包括Html
     * @access private
     * @param string $content 输出内容
     * @param string $charset 模板输出字符集
     * @param string $contentType 输出类型
     * @return mixed
     */
    protected function render($content, $charset='', $contentType=''){
        $charset = $charset ?: Config::get('http_charset');
        $contentType = $contentType ?: Config::get('http_content_type');
        // 网页字符编码
        header('Content-Type:'.$contentType.'; charset='.$charset);
        header('Cache-control: '.Config::get('http_cache_control'));  // 页面缓存控制
        header('X-Powered-By:zsyue');
        // 输出模板文件
        echo $content;
    }
}