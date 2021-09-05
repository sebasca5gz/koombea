<?php
class notFound extends Core implements module {

	public function init() {
		switch($this->_option) {
			default:
				return $this->_404();
		}
	}

	private function _404() {
		$this->setTemplate('404');
		return $this->output();
	}

	public function ajax() {}
}