<?php

require_once 'routs.php';

class Router 
{

    private $routs = ROUTS;
    private $url = null;

    public function __construct()
    {
        $this->url = $this->getUrl();
        $this->requireMethod();
    }


    /** Парсит URL и возвращает 
     * @return string
    */
    private function getUrl()
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
		if(!empty($_GET)) {
			$url = explode('?', $url);
			if(!empty($url[1])) {
				$this->get = '?' . $url[1];
			}
			unset($url[1]);
			$url = $url[0];
        }
        $url = explode('/', $url);
        foreach ($url as $key => $value) {
            if($value === 'backend') {
                unset($url[$key]);
            }
        }

		return implode("/", $url);
    }


    /** Ищет метод в списке и подключает, иначе возвращает NULL */
    private function requireMethod() {
        foreach ($this->routs as $key => $rout) {
            if($this->url === $rout) {
                $path = API . $rout . '.php';
                if(file_exists($path)) {
                    include_once $path;
                    break;
                }
            }
        }
        exit(NULL);
    }
}