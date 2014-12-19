<?php

namespace tmd;

class upload {

    public $allExt = array('jpg','gif','png');
    public $maxSize = 1048576;

    public $rootPath = './upload';

    public $savePath = '';
    public $saveName = '';

    public $error = '';

    public $errMsg = array(
        UPLOAD_ERR_INI_SIZE => '文件大小超出服务器限制',
        UPLOAD_ERR_FORM_SIZE => '文件大小超出表单限制',
        UPLOAD_ERR_PARTIAL => '网络异常(只有部分被上传)',
        UPLOAD_ERR_NO_FILE => '请选择要上传的文件',
        UPLOAD_ERR_NO_TMP_DIR => '服务器错误(找不到临时文件夹)',
        UPLOAD_ERR_CANT_WRITE => '服务器错误(文件写入失败)',
    );

    function __construct($config=array())
    {
        foreach ($config as $key=>$val) {
            isset($this->$key) or trigger_error('Undefined property: '.__CLASS__.'::$'.$key, E_USER_ERROR);
            $this->$key = $val;
        }
    }

    function one($key)
    {
        if (empty($_FILES[$key])) {
            $this->error = '表单缺少文件选择控件';
            return false;
        }

        $file = $_FILES[$key];

        if ($file['error']!==UPLOAD_ERR_OK) { // 有错误
            $this->error = $this->errMsg[ $file['error'] ];
            return false;
        }
        // 后缀名检查
        $ext = self::getExtName($file['name']);
        if (!in_array($ext, $this->allExt)) {
            $this->error = '文件后缀名不被允许';
            return false;
        }
        // 文件大小检查
        if ($this->maxSize >= 0 and $file['size'] > $this->maxSize) {
            $this->error = '文件大小超出限制';
            return false;
        }

        // 保存目录
        if (is_callable($this->savePath)) {
            $savePath = call_user_func($this->savePath, $file);
        }else{
            $savePath = $this->savePath;
        }

        // 保存文件名
        if (is_callable($this->saveName)) {
            $saveName = call_user_func($this->saveName, $file);
        }else{
            $saveName = $this->saveName;
        }
        // 创建目录
        $tmp = $this->rootPath.'/'.$savePath;
        if (!is_dir($tmp)) {
            mkdir($tmp, 0777, true);
        }
        // 绝对路径
        $fullPath = $this->rootPath.'/'.$savePath.'/'.$saveName.'.'.$ext;
        // 移动文件
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            $this->error = '服务器错误(文件移动失败)';
            return false;
        }
        // 返回相对路径
        return $savePath.'/'.$saveName.'.'.$ext;
    }

    function many($key)
    {

    }

    static function getExtName($file)
    {
        return strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }
    static function getMaxSize()
    {
        $size = min(ini_get('upload_max_filesize'), ini_get('post_max_size'));
        if (stripos($size, 'm')) {
            return $size*1048576;
        }
        if (stripos($size, 'g')) {
            return $size*1073741824;
        }
        if (stripos($size, 'k')) {
            return $size*1024;
        }
        return intval($size);
    }
}
