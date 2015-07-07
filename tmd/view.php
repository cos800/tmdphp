<?php
namespace tmd;


class view {
    public $suffix = '.phtml';
    public $debug = true;
    public $prodPath = 'app/_view';

    public $layout = '';
    public $data = [];
    public $viewPath = '';

/*    public function render($file, $dir)
    {
        $this->viewPath = $this->viewPath($dir, $file);
        unset($dir, $file);
        extract($this->data);
        ob_start();
        include $this->viewPath;
        $content = ob_get_clean();


    }*/

    public function viewPath($path, $file)
    {
        if (!$this->debug) {
            $prodPath = realpath($this->prodPath);
            $relaPath = $this->relPath($path, $prodPath);
            $path = $prodPath . '/' . $relaPath;
        }
        return $path . '/' . $file . $this->suffix;
    }

    private function relPath($a, $b)
    {
        $a = strtr($a, '\\', '/');
        $b = strtr($b, '\\', '/');

        $a = explode('/', $a);
        $b = explode('/', $b);

        foreach ($a as $i=>$av) {
            if ($av===$b[$i]) {
                unset($a[$i]);
            }else{
                break;
            }
        }

        return implode('/', $a);
    }

}