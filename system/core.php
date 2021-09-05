<?php
class Core extends db {
	protected $_html = null;
	protected $_option = null;
	protected $_template = null;
	protected $_request = null;

	private $_module;
	private $_title;

	public function __construct() {
		$this->_request = (object) $_REQUEST;
		$this->_option = $this->getParam('option');
	}

	public function load() {
		$this->_ajax();
		$this->_getHeader();
		$this->_getBody();
		$this->_getFooter();
	}

	public function getParam($param) {
		if (isset($this->_request->{$param})) {
			return $this->_request->{$param};
		}
	}

	public function setModule($module) {
		$this->_module = $module;
	}

	public function getModule() {
		return $this->_module;
	}

	public function setTitle($title) {
		$this->_title = $title;
	}

	public function getTitle() {
		return $this->_title;
	}

	public function getSiteUrl() {
		return !empty($_SERVER['SCRIPT_URI'])
			? $_SERVER['SCRIPT_URI']
			: $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . explode('?',$_SERVER['REQUEST_URI'])[0];
	}

	protected function buildUrl(array $params = array()) {
		$url = $this->getSiteUrl();
		if (!empty($params)) {
			foreach($params as $key => $param) {
				$parsedUrl[] = $key . '=' . $param;
			}
			$url.= '?' .implode('&',$parsedUrl);
		}
		return $url;
	}

	protected function moduleExists($module) {
		return file_exists(
			dirname(__FILE__)
			. DIRECTORY_SEPARATOR
			. 'modules'
			. DIRECTORY_SEPARATOR
			. $module
			. DIRECTORY_SEPARATOR
			. $module .'.php'
		);
	}

	protected function setTemplate($template, $ext = '.html') {
		if (!empty($template)) {
			$trace = current(debug_backtrace());
			$template = dirname($trace['file'])
				. DIRECTORY_SEPARATOR
				. 'views'
				. DIRECTORY_SEPARATOR
				. $template
				. $ext;
			if(file_exists($template)) {
				unset($this->_template);
				$this->_template = new template($template);
			} else {
				die('template not found '. $template);
			}
		}
	}

	protected function Output() {
		return $this->_template->output();
	}

	protected function echoOutput() {
		return $this->_template->EchoOutput();
	}

	protected function addParam($arg, $value = null) {
		if (is_array($arg)) {
			$this->_template->AddParam($arg);
		} else {
			$this->_template->AddParam($arg, $value);
		}
	}

	private function _ajax() {
		if (!empty($this->_request->ajax)) {
			$this->setModule($this->_request->module);
			$module = $this->getModule();
			if ($module) {
				$instance = new $module();
				die($instance->ajax());
			}
		}
	}

	private function _getJsModule() {
		$module = $this->getModule();
		if($module) {
			return $this->_parseUrlFiles(glob(dirname(__FILE__) . '/../modules/' . $module . '/js/*.js'));
		}
	}

	private function _getCssModule() {
		$module = $this->getModule();
		if($module) {
			return $this->_parseUrlFiles(glob(dirname(__FILE__) . '/../modules/' . $module . '/css/*.css'));
		}
	}

	private function _parseUrlFiles($files) {
		if(!empty($files)) {
			foreach($files as $k => $file) {
				$filePath = $file;
				unset($files[$k]);
				$files[$k]['file'] = str_replace(
					array( dirname(__FILE__), '/../'),
					array($this->getSiteUrl(),''),
					$file
				);
			}
		}
		return $files;
	}

	private function _getHeader() {
		$this->setTemplate('header');
		$this->addParam(array(
			'js-files' => $this->_getJsModule(),
			'css-files' => $this->_getCssModule(),
			'title' => $this->getTitle()
		));
		return $this->echoOutput();
	}

	private function _getBody() {
		$module = $this->getModule();
		if ($module) {
			$instance = new $module();
			$this->setTemplate('body');
			$this->addParam('content', $instance->init());
			return $this->echoOutput();
		}
	}

	private function _getFooter() {
		$this->setTemplate('footer');
		return $this->echoOutput();
	}
}
