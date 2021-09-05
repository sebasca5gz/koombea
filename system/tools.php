<?php
class tools
{
	public function e($name)
	{
		return $this->exists($name);
	}

	public static function isEmail($email)
	{
		return !empty($email) && preg_match('/^[a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z\p{L}0-9]+(?:[.]?[_a-z\p{L}0-9-])*\.[a-z\p{L}0-9]+$/ui', $email);
	}

	public function exists($name)
	{
		if($_REQUEST[$name])
			return true;
		return false;
	}

	public static function isMd5($md5)
	{
		return preg_match('/^[a-f0-9A-F]{32}$/', $md5);
	}

	public static function isFloat($float)
	{
		return strval((float)$float) == strval($float);
	}

	public static function isUnsignedFloat($float)
	{
		return strval((float)$float) == strval($float) && $float >= 0;
	}

	public static function isImageSize($size)
	{
		return preg_match('/^[0-9]{1,4}$/', $size);
	}

	public static function isName($name)
	{
		return preg_match('/^[^0-9!<>,;?=+()@#"°{}_$%:]*$/u', stripslashes($name));
	}

	public static function isMailName($mail_name)
	{
		return (is_string($mail_name) && preg_match('/^[^<>;=#{}]*$/u', $mail_name));
	}

	public static function isMailSubject($mail_subject)
	{
		return preg_match('/^[^<>]*$/u', $mail_subject);
	}

	public static function isImageTypeName($type)
	{
		return preg_match('/^[a-zA-Z0-9_ -]+$/', $type);
	}

	public static function isPrice($price)
	{
		return preg_match('/^[0-9]{1,10}(\.[0-9]{1,9})?$/', $price);
	}

	public static function isLanguageIsoCode($iso_code)
	{
		return preg_match('/^[a-zA-Z]{2,3}$/', $iso_code);
	}

	public static function isLanguageCode($s)
	{
		return preg_match('/^[a-zA-Z]{2}(-[a-zA-Z]{2})?$/', $s);
	}

	public static function isStateIsoCode($iso_code)
	{
		return preg_match('/^[a-zA-Z0-9]{1,4}((-)[a-zA-Z0-9]{1,4})?$/', $iso_code);
	}

	public static function isNumericIsoCode($iso_code)
	{
		return preg_match('/^[0-9]{2,3}$/', $iso_code);
	}

	public static function isDiscountName($voucher)
	{
		return preg_match('/^[^!<>,;?=+()@"°{}_$%:]{3,32}$/u', $voucher);
	}

	public static function isMessage($message)
	{
		return !preg_match('/[<>{}]/i', $message);
	}

	public static function isCountryName($name)
	{
		return preg_match('/^[a-zA-Z -]+$/', $name);
	}

	public static function isLinkRewrite($link)
	{
		return preg_match('/^[_a-zA-Z0-9\-]+$/', $link);
	}

	public static function isAddress($address)
	{
		return empty($address) || preg_match('/^[^!<>?=+@{}_$%]*$/u', $address);
	}

	public static function isCityName($city)
	{
		return preg_match('/^[^!<>;?=+@#"°{}_$%]*$/u', $city);
	}

	public static function isGenericName($name)
	{
		return empty($name) || preg_match('/^[^<>={}]*$/u', $name);
	}

	public static function isCleanHtml($html, $allow_iframe = false)
	{
		$events = 'onmousedown|onmousemove|onmmouseup|onmouseover|onmouseout|onload|onunload|onfocus|onblur|onchange';
		$events .= '|onsubmit|ondblclick|onclick|onkeydown|onkeyup|onkeypress|onmouseenter|onmouseleave|onerror|onselect|onreset|onabort|ondragdrop|onresize|onactivate|onafterprint|onmoveend';
		$events .= '|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onmove';
		$events .= '|onbounce|oncellchange|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondeactivate|ondrag|ondragend|ondragenter|onmousewheel';
		$events .= '|ondragleave|ondragover|ondragstart|ondrop|onerrorupdate|onfilterchange|onfinish|onfocusin|onfocusout|onhashchange|onhelp|oninput|onlosecapture|onmessage|onmouseup|onmovestart';
		$events .= '|onoffline|ononline|onpaste|onpropertychange|onreadystatechange|onresizeend|onresizestart|onrowenter|onrowexit|onrowsdelete|onrowsinserted|onscroll|onsearch|onselectionchange';
		$events .= '|onselectstart|onstart|onstop';

		if (preg_match('/<[\s]*script/ims', $html) || preg_match('/('.$events.')[\s]*=/ims', $html) || preg_match('/.*script\:/ims', $html))
			return false;

		if (!$allow_iframe && preg_match('/<[\s]*(i?frame|form|input|embed|object)/ims', $html))
			return false;

		return true;
	}

	public static function isPasswd($passwd, $size = 5)
	{
		return (strlen($passwd) >= $size && strlen($passwd) < 255);
	}

	public static function isPasswdAdmin($passwd)
	{
		return Validate::isPasswd($passwd, 8);
	}

	public static function isConfigName($config_name)
	{
		return preg_match('/^[a-zA-Z_0-9-]+$/', $config_name);
	}


	public static function isPhpDateFormat($date_format)
	{
		return preg_match('/^[^<>]+$/', $date_format);
	}

	public static function isDateFormat($date)
	{
		return (bool)preg_match('/^([0-9]{4})-((0?[0-9])|(1[0-2]))-((0?[0-9])|([1-2][0-9])|(3[01]))( [0-9]{2}:[0-9]{2}:[0-9]{2})?$/', $date);
	}

	public static function isDate($date)
	{
		$date = str_replace('/','-',$date);
		if (!preg_match('/^([0-9]{4})-((?:0?[0-9])|(?:1[0-2]))-((?:0?[0-9])|(?:[1-2][0-9])|(?:3[01]))( [0-9]{2}:[0-9]{2}:[0-9]{2})?$/', $date, $matches))
			return false;
		return checkdate((int)$matches[2], (int)$matches[3], (int)$matches[1]);
	}

	public static function isBirthDate($date)
	{

		if (empty($date) || $date == '0000-00-00')
			return true;
		if (preg_match('/^([0-9]{4})-((?:0?[1-9])|(?:1[0-2]))-((?:0?[1-9])|(?:[1-2][0-9])|(?:3[01]))([0-9]{2}:[0-9]{2}:[0-9]{2})?$/', $date, $birth_date))
		{
			if ($birth_date[1] > date('Y') && $birth_date[2] > date('m') && $birth_date[3] > date('d')
				|| $birth_date[1] == date('Y') && $birth_date[2] == date('m') && $birth_date[3] > date('d')
				|| $birth_date[1] == date('Y') && $birth_date[2] > date('m'))
				return false;
			return true;
		}
		return false;
	}

	public static function isBool($bool)
	{
		return $bool === null || is_bool($bool) || preg_match('/^0|1$/', $bool);
	}

	public static function isPhoneNumber($number)
	{
		return preg_match('/^[+0-9. ()-]*$/', $number);
	}

	public static function isPostCode($postcode)
	{
		return empty($postcode) || preg_match('/^[a-zA-Z 0-9-]+$/', $postcode);
	}

	public static function isZipCodeFormat($zip_code)
	{
		if (!empty($zip_code))
			return preg_match('/^[NLCnlc 0-9-]+$/', $zip_code);
		return true;
	}

	public static function isInt($value)
	{
		return ((string)(int)$value === (string)$value || $value === false);
	}

	public static function isUnsignedInt($value)
	{
		return (preg_match('#^[0-9]+$#', (string)$value) && $value < 4294967296 && $value >= 0);
	}

	public static function isPercentage($value,$max = 100)
	{
		return (Validate::isFloat($value) && $value >= 0 && $value <= $max);
	}

	public static function isUnsignedId($id)
	{
		return Validate::isUnsignedInt($id);
	}

	public static function isNullOrUnsignedId($id)
	{
		return $id === null || Validate::isUnsignedId($id);
	}

	public static function isColor($color)
	{
		return preg_match('/^(#[0-9a-fA-F]{6}|[a-zA-Z0-9-]*)$/', $color);
	}

	public static function isUrl($url)
	{
		return preg_match('/^[~:#,$%&_=\(\)\.\? \+\-@\/a-zA-Z0-9]+$/', $url);
	}

	public static function isUrlOrEmpty($url)
	{
		return empty($url) || Validate::isUrl($url);
	}

	public static function isAbsoluteUrl($url)
	{
		if (!empty($url))
			return preg_match('/^https?:\/\/[$~:;#,%&_=\(\)\[\]\.\? \+\-@\/a-zA-Z0-9]+$/', $url);
		return true;
	}

	public static function isFileName($name)
	{
		return preg_match('/^[a-zA-Z0-9_.-]+$/', $name);
	}

	public static function isSubDomainName($domain)
	{
		return preg_match('/^[a-zA-Z0-9-_]*$/', $domain);
	}

	public static function isLabel($label)
	{
		return (preg_match('/^[^{}<>]*$/u', $label));
	}

	public static function isString($data)
	{
		return is_string($data);
	}

	public static function isBoolId($ids)
	{
		return (bool)preg_match('#^[01]_[0-9]+$#', $ids);
	}

	public static function isSerializedArray($data)
	{
		return $data === null || (is_string($data) && preg_match('/^a:[0-9]+:{.*;}$/s', $data));
	}

	public static function isCoordinate($data)
	{
		return $data === null || preg_match('/^\-?[0-9]{1,8}\.[0-9]{1,8}$/s', $data);
	}

	public static function isArrayWithIds($ids)
	{
		if (count($ids))
			foreach ($ids as $id)
				if ($id == 0 || !Validate::isUnsignedInt($id))
					return false;
		return true;
	}

	public static function isApe($ape)
	{
		return (bool)preg_match('/^[0-9]{3,4}[a-zA-Z]{1}$/s', $ape);
	}
}
