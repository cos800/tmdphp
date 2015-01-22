<?php

namespace tmd;

class upload {

    public $allExt = array('jpg','gif','png');
    public $maxSize = 1048576;

    public $rootPath = './upload';
    public $savePath = '';
    public $saveName = '';

    public $isMany = false;

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

    function one($file)
    {
        if ($file['error']!==UPLOAD_ERR_OK) { // 有错误
            $file['error_msg'] = $this->errMsg[ $file['error'] ];
            return $file;
        }

        // 后缀名检查
        $ext = $file['ext'] = self::getFileExt($file['name']);
        if (!in_array($ext, $this->allExt)) {
            $file['error_msg'] = '文件后缀名不被允许';
            return $file;
        }

        // 文件大小检查
        if ($this->maxSize >= 0 and $file['size'] > $this->maxSize) {
            $file['error_msg'] = '文件大小超出限制';
            return $file;
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
        $file['path_abs'] = $this->rootPath.'/'.$savePath.'/'.$saveName.'.'.$ext;
        // 移动文件
        if (!move_uploaded_file($file['tmp_name'], $file['path_abs'])) {
            $file['error_msg'] = '服务器错误(文件移动失败)';
            return $file;
        }

        // 相对路径
        $file['path_rel'] = $savePath.'/'.$saveName.'.'.$ext;
        return $file;
    }

    function run($key)
    {
        if (empty($_FILES[$key])) {
            $this->isMany = false;
            return array(
                'error_msg' => '表单字段名错误',
            );
        }

        $files = $_FILES[$key];

        if (is_array($files['name'])) {
            $this->isMany = true;

            $ret = array();
            foreach ($files['name'] as $k=>$v) {
                $file = array(
                    'name' => $files['name'][$k],
                    'type' => $files['type'][$k],
                    'tmp_name' => $files['tmp_name'][$k],
                    'error' => $files['error'][$k],
                    'size' => $files['size'][$k],
                );
                $ret[] = $this->one($file);
            }
            return $ret;
        }else{
            $this->isMany = false;

            return $this->one($files);
        }
    }

    static function getFileExt($file)
    {
        return strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }

}
