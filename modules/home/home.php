<?php
class Home extends Core implements module {

	private $_isLogged;
	private $_errors;

	public function init() {
		switch($this->_option) {
			case 'register':
				return $this->_registerForm();
			break;
			case 'login':
				$this->_loginForm();
			break;
			default:
				return $this->_home();
		}
	}

	public function ajax() {
		$response = array();
		switch ($this->_request->option) {
			case 'close':
				return $this->processCloseSession();
			break;
			case 'login':
				return $this->processLogin();
			break;
			case 'register':
				return $this->processRegister();
			break;
			case 'registerform':
				$response['option'] = 'registerForm';
				$response['response'] = $this->_registerForm();
			break;
			case 'loginform':
				$response['option'] = 'loginForm';
				$response['response'] = $this->_loginForm();
			break;

		}
		return json_encode($response);
	}

	private function processCloseSession() {
		unset($_SESSION['user']);
		session_destroy();
		return json_encode(array(
			'option' => 'refresh',
			'error' => false
		));
	}

	private function processLogin() {
		$this->validateUserForm('login');
		if (empty($this->_errors)) {
			$_SESSION['user'] = $this->getUser($this->_request->data['email'], $this->_request->data['password']);
			$response = array(
				'option' => 'refresh',
				'error' => false
			);
		} else {
			$response = array(
				'option' => 'message',
				'error' => true,
				'message' => implode('<br>', $this->_errors)
			);
		}
		return json_encode($response);
	}

	private function validateUserForm($type) {
		if (empty($this->_request->data['email'])) {
			$this->_errors[] = 'Ingrese el campo email';
		} else {
			if (!Tools::isEmail($this->_request->data['email'])) {
				$this->_errors[] = 'Ingrese un email válido';
			}

			if ($type === 'register') {
				$query = $this->DBH()->prepare('SELECT * FROM users WHERE email = :email');
				$query->bindParam(':email', $this->_request->data['email']);
				$query->execute();
				if ($query->rowCount()) {
					$this->_errors[] = 'Ya existe un usuario registrado con el email <em>' .$this->_request->data['email']. '</em>';
				}
			}
		}

		if (empty($this->_request->data['password'])) {
			$this->_errors[] = 'Ingrese el password';
		} else {
			if (!Tools::isPasswd($this->_request->data['password'])) {
				$this->_errors[] = 'Ingrese un password válido';
			}
		}

		if ($type === 'login'
			&& !empty($this->_request->data['email'])
			&& !empty($this->_request->data['password'])
		) {
			$user = $this->getUser($this->_request->data['email'], $this->_request->data['password'], true);
			if ($user === 0) {
				$this->_errors[] = 'Acceso denegado';
			}
		}
	}

	private function getUser($email, $pass, $checkExists = false) {
		$pass = md5($pass);
		$query = $this->DBH()->prepare('SELECT id, email FROM users WHERE email = :email AND password = :password');
		$query->bindParam(':email', $email);
		$query->bindParam(':password', $pass);
		$query->execute();
		if ($checkExists) {
			return $query->rowCount();
		}

		return $query->fetchAll(PDO::FETCH_OBJ);
	}

	private function processRegister() {
		$this->validateUserForm('register');
		if (empty($this->_errors)) {
			try {
				$password = md5($this->_request->data['password']);
				$email = mb_strtolower($this->_request->data['email']);
				$query = $this->DBH()->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
				$query->bindParam(':email', $email);
				$query->bindParam(':password', $password);
				$query->execute();
				$response = array(
					'option' => 'message',
					'error' => false,
					'message' => 'Registro exitoso'
				);
			} catch (PDOException $e){
				$response = array(
					'option' => 'message',
					'error' => true,
					'message' => 'Se presento un error al crear el registro'
				);
			}
		} else {
			$response = array(
				'option' => 'message',
				'error' => true,
				'message' => implode('<br>', $this->_errors)
			);
		}
		return json_encode($response);
	}

	protected function isLogged() {
		return !empty($_SESSION['user']);
	}

	private function _home() {
		$content = $this->_getContent();
		$this->setTemplate('home');
		$this->addParam(array(
			'isLogged' => $this->isLogged(),
			'content' => $content
		));
		return $this->output();
	}

	private function _getContent() {
		return $this->isLogged()
			? $this->_uploadForm()
			: $this->_loginForm();
	}

	private function _uploadForm() {
		$this->setTemplate('upload');
		return $this->output();
	}

	private function _loginForm() {
		$userForm = $this->_getUserForm('login');
		$this->setTemplate('login');
		$this->addparam(array(
			'form' => $userForm
		));
		return $this->output();
	}

	private function _registerForm() {
		$userForm = $this->_getUserForm('register');
		$this->setTemplate('register');
		$this->addparam(array(
			'form' => $userForm
		));
		return $this->output();
	}

	private function _getUserForm($option) {
		$this->setTemplate('userform');
		$this->addParam(array(
			'backUrl' => $this->buildUrl(),
			'option' => $option
		));
		return $this->output();
	}
}