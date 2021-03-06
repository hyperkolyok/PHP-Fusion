<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2014 PHP-Fusion Inc.
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: defender.inc.php
| Author : Frederick MC Chan (Hien)
| Co-Author: Dan C (JoiNNN)
| Version : 9.0.5 (please update every commit)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

class defender {
	public $debug = false;
	public $ref = array();
	public $error_content = array();
	public $error_title = '';
	/** Declared by Form Sanitizer */
	public $field = array();
	public $field_name = '';
	public $field_value = '';
	public $field_default = '';
	public $field_config = array('type' => '',
		'value' => '',
		'default' => '',
		'name' => '',
		'id' => '',
		'safemode' => '',
		'path' => '',
		'thumbnail_1' => '',
		'thumbnail_2' => '',
	);

	/* Sanitize Fields Automatically */
	public function defender() {
		global $locale;
		/*
		 * Keep this include in the constructor
		 * This solution was needed to load the defender.inc.php before
		 * defining LOCALESET
		 */
		include LOCALE.LOCALESET."defender.php";
		require_once INCLUDES.'notify/notify.inc.php';
		// declare the validation rules and assign them
		// type of fields vs type of validator
		$validation_rules_assigned = array(
			'textbox' => 'textbox',
			'dropdown' => 'textbox',
			'password' => 'password',
			'textarea' => 'textbox',
			'number' => 'number',
			'email' => 'email',
			'date' => 'date',
			'timestamp' => 'date',
			'color' => 'textbox',
			'address' => 'address',
			'name' => 'name',
			'url' => 'url',
			'image' => 'image',
			'file' => 'file',
			'document' => 'document',
		);
		// execute sanitisation rules at point blank precision using switch
		try {
			if (!empty($this->field_config['type'])) {
				switch ($validation_rules_assigned[$this->field_config['type']]) {
					case 'textbox':
						return $this->verify_text();
						break;
					case 'date':
						return $this->verify_date();
						break;
					case 'password':
						return $this->verify_password();
						break;
					case 'email':
						return $this->verify_email();
						break;
					case 'number' :
						return $this->verify_number();
						break;
					case 'file' :
						return $this->verify_file_upload();
						break;
					case 'url' :
						return $this->verify_url();
						break;
					case 'name':
						$name = $this->field_name;

						if ($this->field_config['required'] && !$_POST[$name][0]) {
							$this->stop();
							$this->addError($this->field_config['id']);
							$this->addHelperText($this->field_config['id'].'-firstname', $locale['firstname_error']);
							$this->addNotice($locale['firstname_error']);
						}
						if ($this->field_config['required'] && !$_POST[$name][1]) {
							$this->stop();
							$this->addError($this->field_config['id']);
							$this->addHelperText($this->field_config['id'].'-lastname', $locale['lastname_error']);
							$this->addNotice($locale['lastname_error']);
						}
						if (!defined('FUSION_NULL')) {
							$return_value = $this->verify_text();
							return $return_value;
						}
						break;
					case 'address':
						$name = $this->field_name;
						//$def = $this->get_full_options($this->field_config);
						if ($this->field_config['required'] && !$_POST[$name][0]) {
							$this->stop();
							$this->addError($this->field_config['id']);
							$this->addHelperText($this->field_config['id'].'-street', $locale['street_error']);
							$this->addNotice($locale['street_error']);
						}
						if ($this->field_config['required'] && !$_POST[$name][2]) {
							$this->stop();
							$this->addError($this->field_config['id']);
							$this->addHelperText($this->field_config['id'].'-country', $locale['country_error']);
							$this->addNotice($locale['country_error']);
						}
						if ($this->field_config['required'] && !$_POST[$name][3]) {
							$this->stop();
							$this->addError($this->field_config['id']);
							$this->addHelperText($this->field_config['id'].'-state', $locale['state_error']);
							$this->addNotice($locale['state_error']);
						}
						if ($this->field_config['required'] && !$_POST[$name][4]) {
							$this->stop();
							$this->addError($this->field_config['id']);
							$this->addHelperText($this->field_config['id'].'-city', $locale['city_error']);
							$this->addNotice($locale['city_error']);
						}
						if ($this->field_config['required'] && !$_POST[$name][5]) {
							$this->stop();
							$this->addError($this->field_config['id']);
							$this->addHelperText($this->field_config['id'].'-postcode', $locale['postcode_error']);
							$this->addNotice($locale['postcode_error']);
						}
						if (!defined('FUSION_NULL')) {
							$return_value = $this->verify_text();
							return $return_value;
						}
						break;
					case 'image' :
						return $this->verify_image_upload();
						break;
					case 'document':
						$name = $this->field_name;
						if ($this->field_config['required'] && !$_POST[$name][0]) {
							$this->stop();
							$this->addError($this->field_config['id']);
							$this->addHelperText($this->field_config['id'].'-doc_type', $locale['doc_type_error']);
							$this->addNotice($locale['doc_type_error']);
						}
						if ($this->field_config['required'] && !$_POST[$name][1]) {
							$this->stop();
							$this->addError($this->field_config['id']);
							$this->addHelperText($this->field_config['id'].'-doc_series', $locale['doc_series_error']);
							$this->addNotice($locale['doc_series_error']);
						}
						if ($this->field_config['required'] && !$_POST[$name][2]) {
							$this->stop();
							$this->addError($this->field_config['id']);
							$this->addHelperText($this->field_config['id'].'-doc_number', $locale['doc_number_error']);
							$this->addNotice($locale['doc_number_error']);
						}
						if ($this->field_config['required'] && !$_POST[$name][3]) {
							$this->stop();
							$this->addError($this->field_config['id']);
							$this->addHelperText($this->field_config['id'].'-doc_authority', $locale['doc_authority_error']);
							$this->addNotice($locale['doc_authority_error']);
						}
						if ($this->field_config['required'] && !$_POST[$name][4]) {
							$this->stop();
							$this->addError($this->field_config['id']);
							$this->addHelperText($this->field_config['id'].'-date_issue', $locale['date_issue_error']);
							$this->addNotice($locale['date_issue_error']);
						}
						if (!defined('FUSION_NULL')) {
							$return_value = $this->verify_text();
							return $return_value;
						}
						break;
					default:
						$this->stop();
						$this->addNotice($this->field_name);
						$this->addNotice('Verification on unknown type of fields is prohibited.');
				}
			} else {
				return $this->field_default;
				//$this->stop();
				//$message = $this->field_name.' has no value.'; // this has no value and must pushed out.
				//$this->addNotice($message);
			}
		} catch (Exception $e) {
			$error_message = $e->getMessage();
			$this->stop();
			$this->addNotice($error_message);
		}
	}


	static function set_storage($key, $value, $object_type = 'sessionStorage') { // you can change to sessionStorage
		//$object_type = $permanent ? 'localStorage' : 'sessionStorage';
		add_to_jquery("if (typeof(Storage) != 'undefined') { $object_type.setItem('$key', '$value'); } else { console.log('Sorry, your browser does not support Web Storage...');	}");
	}
	static function get_storage($key, $object_type = 'sessionStorage') {

	}

	/**
	 * PHP-Fusion 10 Prototype
	 * Returns a hashed token to a static rule
	 * @param $key
	 * @return string
	 */
	static function token($key) {
		global $userdata;
		$user_id = defender::set_sessionUserID();
		$algo = fusion_get_settings('password_algorithm');
		$authkey = $user_id.str_replace(' ', '',$key).SECRET_KEY;
		$salt = md5(isset($userdata['user_salt']) ? $userdata['user_salt'].SECRET_KEY_SALT : SECRET_KEY_SALT);
		return hash_hmac($algo, $authkey, $salt); // this is the hash by the id.
	}

	/**
	 * PHP-Fusion 10 Prototype
	 * Returns a genuine hashed token provided a valid hash is given
	 * @param $key
	 * @param $hash
	 * @return bool|string
	 */
	static function return_token($key, $hash) {
		global $userdata;
		$user_id = defender::set_sessionUserID();
		$algo = fusion_get_settings('password_algorithm');
		$authkey = $user_id.str_replace(' ', '',$key).SECRET_KEY;
		$salt = md5(isset($userdata['user_salt']) ? $userdata['user_salt'].SECRET_KEY_SALT : SECRET_KEY_SALT);
		if ($hash == hash_hmac($algo, $authkey, $salt)) {
			return hash_hmac($algo, $authkey, $salt); // this is the hash by the id.;
		}
		return false;
	}

	/**
	 * The UserID
	 * No $userName because it can be changed and tampered via Edit Profile.
	 * Using IP address extends for guest
	 * @return mixed
	 */
	static function set_sessionUserID() {
		global $userdata;
		// using . will yield invalid token format.
		return isset($userdata['user_id']) && !isset($_POST['login']) ? (int) $userdata['user_id'] : str_replace('.', '-', USER_IP);
	}

	/* Adds the field sessions on document load */
	static function add_field_session(array $array) {
		$_SESSION['form_fields'][self::set_sessionUserID()][$_SERVER['PHP_SELF']][$array['input_name']] = $array;
	}

	/**
	 * Prints specific User Form Field Traces.
	 * $user_id as false to see yourself
	 * $user_id as integer to see specific user
	 * @return string
	 */
	static function display_user_field_session($user_id = FALSE) {
		$user_id = $user_id && isnum($user_id) && dbcount("(user_id)", DB_USERS, "user_id='".intval($user_id)."'") ? $user_id : self::set_sessionUserID();
		if (isset($_SESSION['form_fields'][$user_id][$_SERVER['PHP_SELF']])) {
			print_p($_SESSION['form_fields'][$user_id][$_SERVER['PHP_SELF']]);
		} else {
			print_p(" $user_id on ".$_SERVER['PHP_SELF']." not found ");
		}
	}

	/* Destroys the user field session */
	public static function unset_field_session() {
		unset($_SESSION['form_fields'][self::set_sessionUserID()]);
	}

	// Field Validation Output

	/**
	 * Jquery add has-error CSS class to field input container on error
	 * @param $id - field id
	 */
	static function addError($id) {
		add_to_jquery("$('#$id-field').addClass('has-error');");
	}

	/**
	 * Jquery append helper text
	 * @param $id
	 * @param $content
	 */
	static function addHelperText($id, $content) {
		// add prevention of double entry should the fields are the same id.
		if (!defined(".$id-help")) {
			define(".$id-help", TRUE);
			add_to_jquery("
                $('#$id-help').addClass('label label-danger m-t-5 p-5 display-inline-block');
                $('#$id-help').append('$content');
			");
		}
	}

	/* Inject form notice */
	public function addNotice($content) {
		// add prevention of double entry should the fields are the same id.
		$this->error_content[] = $content;
		return $this->error_content;
	}

	public function setNoticeTitle($title) {
		$this->error_title = $title;
	}

	/* Aggregate notices */
	public function Notice() {
		if (isset($this->error_content)) {
			return $this->error_content;
		}
		return FALSE;
	}

	public function showNotice() {
		global $locale;
		$html = '';
		$title = $this->error_title ? $this->error_title : $locale['validate_title'];
		if (!empty($this->error_content)) {
			$html .= "<div id='close-message'>\n";
			$html .= "<div class='admin-message alert alert-danger alert-dismissable' role='alert'>\n";
			$html .= "<span class='text-bigger'><strong>".$title."</strong></p><br/>\n";
			$html .= "<ul id='error_list'>\n";
			foreach ($this->error_content as $notices) {
				$html .= "<li>".$notices."</li>\n";
			}
			$html .= "</ul>\n";
			$html .= "</div>\n</div>\n";
		}
		return $html;
	}

	/* Inject FUSION_NULL */
	static function stop($ref = FALSE, $show_notice = FALSE) {
		if ($ref && $show_notice && !defined('FUSION_NULL')) {
			notify('There was an error processing your request.', $ref);
			define('FUSION_NULL', TRUE);
		} else {
			if (!defined('FUSION_NULL')) define('FUSION_NULL', TRUE);
		}
	}

	// Field Verifications Rules

	/* validate and sanitize a text
 	 * accepts only 50 characters + @ + 4 characters
 	 */
	protected function verify_text() {
		global $locale;
		if (is_array($this->field_value)) {
			$vars = array();
			foreach ($this->field_value as $val) {
				$vars[] = stripinput(trim(preg_replace("/ +/i", " ", censorwords($val))));
			}
			$value = implode('|', $vars); // this is where the pipe is.
		} else {
			$value = stripinput(trim(preg_replace("/ +/i", " ", censorwords($this->field_value)))); // very strong sanitization.
		}
		if ($this->field_config['safemode'] == 1) {
			if (!preg_check("/^[-0-9A-Z_@\s]+$/i", $this->field_value)) { // invalid chars
				$this->stop();
				$this->addError($this->field_config['id']);
				$this->addHelperText($this->field_config['id'], sprintf($locale['df_400'], $this->field_config['title'])); // maybe name, maybe
				$this->addNotice(sprintf($locale['df_400'], $this->field_config['title']));
			} else {
				$return_value = ($value) ? $value : $this->field_default;
				return $return_value;
			}
		} else {
			if ($value) {
				return $value;
			} else {
				return $this->field_default;
			}
		}
	}

	/* validate an email address
	 * accepts only 50 characters + @ + 4 characters
	 */
	protected function verify_email() {
		global $locale;
		if ($this->field_value) {
			$value = stripinput(trim(preg_replace("/ +/i", " ", $this->field_value)));
			if (preg_check("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $value)) {
				return $value;
			} else {
				$this->stop();
				$this->addError($this->field_config['id']);
				$this->addHelperText($this->field_config['id'], sprintf($locale['df_401'], $this->field_config['title']));
				$this->addNotice(sprintf($locale['df_401'], $this->field_config['title']));
			}
		} else {
			return $this->field_default;
		}
	}

	/* validate a valid password
	 * accepts minimum of 8 and maximum of 64 due to encrypt limit
	 * returns a default if blank
	 */
	protected function verify_password() {
		global $locale;
		// add min length, add max length, add strong password into roadmaps.
		if (preg_match("/^[0-9A-Z@!#$%&\/\(\)=\-_?+\*\.,:;]{8,64}$/i", $this->field_value)) {
			return $this->field_value;
		} else {
			// invalid password
			$this->stop();
			$this->addError($this->field_config['id']);
			$this->addHelperText($this->field_config['id'], sprintf($locale['df_402'], $this->field_config['title']));
			$this->addNotice(sprintf($locale['df_402'], $this->field_config['title']));
		}
	}

	/* validate a valid number
	 * accepts only integer and decimal .
	 * returns a default if blank
	 */
	protected function verify_number() {
		global $locale;
		if (is_array($this->field_value)) {
			$vars = array();
			foreach ($this->field_value as $val) {
				$vars[] = stripinput($val);
			}
			$value = implode(',', $vars);
		} else {
			$value = intval(stripinput($this->field_value));
		}
		if ($value) {
			if (is_numeric($this->field_value)) {
				return $this->field_value;
			} else {
				$this->stop();
				$this->addError($this->field_config['id']);
				$this->addHelperText($this->field_config['id'], sprintf($locale['df_403'], $this->field_config['title']));
				$this->addNotice(sprintf($locale['df_403'], $this->field_config['title']));
			}
		} else {
			return $this->field_default;
		}
	}

	/* validate a valid url
	* require path.
	* returns a default if blank
	*/
	protected function verify_url() {
		if ($this->field_value) {
			return filter_var($this->field_value, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			//return cleanurl($this->field_value);
		} else {
			return $this->field_default;
		}
	}

	/* returns 10 integer timestamp
	 * accepts date format in - , / and . delimiters
	 * returns a default if blank
	 */
	protected function verify_date() {
		global $locale;
		// pair each other to determine which is month.
		// the standard value for dynamics is day-month-year.
		// the standard value for mysql is year-month-day.
		if ($this->field_value) {
			if (stristr($this->field_value, '-')) {
				$this->field_value = explode('-', $this->field_value);
			} elseif (stristr($this->field_value, '/')) {
				$this->field_value = explode('/', $this->field_value);
			} else {
				$this->field_value = explode('.', $this->field_value);
			}
			if (checkdate($this->field_value[1], $this->field_value[0], $this->field_value[2])) {
				if ($this->field_config['type'] == 'timestamp') {
					return mktime(0, 0, 0, $this->field_value[1], $this->field_value[0], $this->field_value[2]);
				} elseif ($this->field_config['type'] == 'date') {
					// year month day.
					$return_value = $this->field_value[2]."-".$this->field_value[1]."-".$this->field_value[0];
					return $return_value;
				}
			} else {
				$this->stop();
				$this->addError($this->field_config['id']);
				$this->addHelperText($this->field_config['id'], sprintf($locale['df_404'], $this->field_config['title']));
				$this->addNotice(sprintf($locale['df_404'], $this->field_config['title']));
			}
		} else {
			return $this->field_default;
		}
	}

	/* Verify and upload image on success. Returns array on file, thumb and thumb2 file names */
	/* You can use this function anywhere whether bottom or top most of your codes - order unaffected */
	protected function verify_image_upload() {
		global $locale;
		require_once INCLUDES."infusions_include.php";
		if ($this->field_config['multiple']) {
			$target_folder = $this->field_config['path'];
			$target_width = $this->field_config['max_width'];
			$target_height = $this->field_config['max_height'];
			$max_size = $this->field_config['max_byte'];
			$delete_original = $this->field_config['delete_original'];
			$thumb1 = $this->field_config['thumbnail'];
			$thumb2 = $this->field_config['thumbnail2'];
			$thumb1_ratio = 1;
			$thumb1_folder = $this->field_config['path'].$this->field_config['thumbnail_folder']."/";
			$thumb1_suffix = $this->field_config['thumbnail_suffix'];
			$thumb1_width = $this->field_config['thumbnail_w'];
			$thumb1_height = $this->field_config['thumbnail_h'];
			$thumb2_ratio = 0;
			$thumb2_folder = $this->field_config['path'].$this->field_config['thumbnail_folder']."/";
			$thumb2_suffix = $this->field_config['thumbnail2_suffix'];
			$thumb2_width = $this->field_config['thumbnail2_w'];
			$thumb2_height = $this->field_config['thumbnail2_h'];
			$query = '';
			if (!empty($_FILES[$this->field_config['input_name']]['name'])) {
				$result = array();
				for($i = 0; $i <= count($_FILES[$this->field_config['input_name']]['name'])-1; $i++) {
					if (is_uploaded_file($_FILES[$this->field_config['input_name']]['tmp_name'][$i])) {
						$image = $_FILES[$this->field_config['input_name']];
						$target_name = $_FILES[$this->field_config['input_name']]['name'][$i];
						if ($target_name != "" && !preg_match("/[^a-zA-Z0-9_-]/", $target_name)) {
							$image_name = $target_name;
						} else {
							$image_name = stripfilename(substr($image['name'][$i], 0, strrpos($image['name'][$i], ".")));
						}
						$image_ext = strtolower(strrchr($image['name'][$i], "."));
						$image_res = array();
						if (filesize($image['tmp_name'][$i]) > 10 && @getimagesize($image['tmp_name'][$i])) {
							$image_res = @getimagesize($image['tmp_name'][$i]);
						}
						$image_info = array(
							"image" => FALSE,
							"image_name" => $image_name.$image_ext,
							"image_ext" => $image_ext,
							"image_size" => $image['size'],
							"image_width" => $image_res[0],
							"image_height" => $image_res[1],
							"thumb1" => FALSE,
							"thumb1_name" => "",
							"thumb2" => FALSE,
							"thumb2_name" => "",
							"error" => 0,
							);
						if ($image_ext == ".gif") {
							$filetype = 1;
						} elseif ($image_ext == ".jpg") {
							$filetype = 2;
						} elseif ($image_ext == ".png") {
							$filetype = 3;
						} else {
							$filetype = FALSE;
						}
						if ($image['size'][$i] > $max_size) {
							// Invalid file size
							$image_info['error'] = 1;
						} elseif (!$filetype || !verify_image($image['tmp_name'][$i])) {
							// Unsupported image type
							$image_info['error'] = 2;
						} elseif ($image_res[0] > $target_width || $image_res[1] > $target_height) {
							// Invalid image resolution
							$image_info['error'] = 3;
						} else {
							if (!file_exists($target_folder)) {
								mkdir($target_folder, 0755);
							}
							$image_name_full = filename_exists($target_folder, $image_name.$image_ext);
							$image_name = substr($image_name_full, 0, strrpos($image_name_full, "."));
							$image_info['image_name'] = $image_name_full;
							$image_info['image'] = TRUE;
							move_uploaded_file($image['tmp_name'][$i], $target_folder.$image_name_full);
							if (function_exists("chmod")) {
								chmod($target_folder.$image_name_full, 0755);
							}
							if ($query && !dbquery($query)) {
								// Invalid query string
								$image_info['error'] = 4;
								unlink($target_folder.$image_name_full);
							} elseif ($thumb1 || $thumb2) {
								require_once INCLUDES."photo_functions_include.php";
								$noThumb = FALSE;
								if ($thumb1) {
									if ($image_res[0] <= $thumb1_width && $image_res[1] <= $thumb1_height) {
										$noThumb = TRUE;
										$image_info['thumb1_name'] = $image_info['image_name'];
										$image_info['thumb1'] = TRUE;
									} else {
										if (!file_exists($thumb1_folder)) {
											mkdir($thumb1_folder, 0755, TRUE);
										}
										$image_name_t1 = filename_exists($thumb1_folder, $image_name.$thumb1_suffix.$image_ext);
										$image_info['thumb1_name'] = $image_name_t1;
										$image_info['thumb1'] = TRUE;
										if ($thumb1_ratio == 0) {
											createthumbnail($filetype, $target_folder.$image_name_full, $thumb1_folder.$image_name_t1, $thumb1_width, $thumb1_height);
										} else {
											createsquarethumbnail($filetype, $target_folder.$image_name_full, $thumb1_folder.$image_name_t1, $thumb1_width);
										}
									}
								}
								if ($thumb2) {
									if ($image_res[0] < $thumb2_width && $image_res[1] < $thumb2_height) {
										$noThumb = TRUE;
										$image_info['thumb2_name'] = $image_info['image_name'];
										$image_info['thumb2'] = TRUE;
									} else {
										if (!file_exists($thumb2_folder)) {
											mkdir($thumb2_folder, 0755, TRUE);
										}
										$image_name_t2 = filename_exists($thumb2_folder, $image_name.$thumb2_suffix.$image_ext);
										$image_info['thumb2_name'] = $image_name_t2;
										$image_info['thumb2'] = TRUE;
										if ($thumb2_ratio == 0) {
											createthumbnail($filetype, $target_folder.$image_name_full, $thumb2_folder.$image_name_t2, $thumb2_width, $thumb2_height);
										} else {
											createsquarethumbnail($filetype, $target_folder.$image_name_full, $thumb2_folder.$image_name_t2, $thumb2_width);
										}
									}
								}
								if ($delete_original && !$noThumb) {
									unlink($target_folder.$image_name_full);
									$image_info['image'] = FALSE;
								}
							}
						}
					} else {
						$image_info = array("error" => 5);
					}
					if ($image_info['error'] != 0) {
						//$this->stop();
						$this->addError($this->field_config['id']);
						switch ($image_info['error']) {
							case 1: // Invalid file size
								$this->addNotice(sprintf($locale['df_416'], parsebytesize($this->field_config['max_byte'])));
								$this->addHelperText($this->field_config['id'], sprintf($locale['df_416'], parsebytesize($this->field_config['max_byte'])));
								break;
							case 2: // Unsupported image type
								$this->addNotice(sprintf($locale['df_417'], ".gif .jpg .png"));
								$this->addHelperText($this->field_config['id'], sprintf($locale['df_417'], ".gif .jpg .png"));
								break;
							case 3: // Invalid image resolution
								$this->addNotice(sprintf($locale['df_421'], $this->field_config['max_width']." x ".$this->field_config['max_height']));
								$this->addHelperText($this->field_config['id'], sprintf($locale['df_421'], $this->field_config['max_width'], $this->field_config['max_height']));
								break;
							case 4: // Invalid query string
								$this->addNotice($locale['df_422']);
								$this->addHelperText($this->field_config['id'], $locale['df_422']);
								break;
							case 5: // Image not uploaded
								$this->addNotice($locale['df_423']);
								$this->addHelperText($this->field_config['id'], $locale['df_423']);
								break;
						}
					} else {
						$result[$i] = $image_info;
					}
				} // end for
				return $result;
			} else {
				return array();
			}
		} else {
			if (!empty($_FILES[$this->field_config['input_name']]['name']) && is_uploaded_file($_FILES[$this->field_config['input_name']]['tmp_name']) && !defined('FUSION_NULL')) {
				$upload = upload_image($this->field_config['input_name'], $_FILES[$this->field_config['input_name']]['name'], $this->field_config['path'], $this->field_config['max_width'], $this->field_config['max_height'], $this->field_config['max_byte'], $this->field_config['delete_original'], $this->field_config['thumbnail'], $this->field_config['thumbnail2'], 1, $this->field_config['path'].$this->field_config['thumbnail_folder']."/", $this->field_config['thumbnail_suffix'], $this->field_config['thumbnail_w'], $this->field_config['thumbnail_h'], 0, $this->field_config['path'].$this->field_config['thumbnail_folder']."/", $this->field_config['thumbnail2_suffix'], $this->field_config['thumbnail2_w'], $this->field_config['thumbnail2_h']);
				if ($upload['error'] != 0) {
					$this->stop();
					$this->addError($this->field_config['id']);
					switch ($upload['error']) {
						case 1: // Invalid file size
							$this->addNotice(sprintf($locale['df_416'], parsebytesize($this->field_config['max_byte'])));
							$this->addHelperText($this->field_config['id'], sprintf($locale['df_416'], parsebytesize($this->field_config['max_byte'])));
							break;
						case 2: // Unsupported image type
							$this->addNotice(sprintf($locale['df_417'], ".gif .jpg .png"));
							$this->addHelperText($this->field_config['id'], sprintf($locale['df_417'], ".gif .jpg .png"));
							break;
						case 3: // Invalid image resolution
							$this->addNotice(sprintf($locale['df_421'], $this->field_config['max_width']." x ".$this->field_config['max_height']));
							$this->addHelperText($this->field_config['id'], sprintf($locale['df_421'], $this->field_config['max_width']." x ".$this->field_config['max_height']));
							break;
						case 4: // Invalid query string
							$this->addNotice($locale['df_422']);
							$this->addHelperText($this->field_config['id'], $locale['df_422']);
							break;
						case 5: // Image not uploaded
							$this->addNotice($locale['df_423']);
							$this->addHelperText($this->field_config['id'], $locale['df_423']);
							break;
					}
				} else {
					return $upload;
				}
			} else {
				return array();
			}
		}
	}

	protected function verify_file_upload() {
		global $locale;
		require_once INCLUDES."infusions_include.php";
		if (!empty($_FILES[$this->field_config['input_name']]['name']) && is_uploaded_file($_FILES[$this->field_config['input_name']]['tmp_name']) && !defined('FUSION_NULL')) {
			$upload = upload_file($this->field_config['input_name'], $_FILES[$this->field_config['input_name']]['name'], $this->field_config['path'], $this->field_config['valid_ext'], $this->field_config['max_byte']);
			if ($upload['error'] != 0) {
				$this->stop();
				$this->addError($this->field_config['id']);
				switch ($upload['error']) {
					case 1: // Maximum file size exceeded
						$this->addNotice(sprintf($locale['df_416'], parsebytesize($this->field_config['max_byte'])));
						$this->addHelperText($$this->field_config['id'], $locale['df_416']);
						break;
					case 2: // Invalid File extensions
						$this->addNotice(sprintf($locale['df_417'], $this->field_config['valid_ext']));
						$this->addHelperText($$this->field_config['id'], $locale['df_417']);
						break;
					case 3: // Invalid Query String
						$this->addNotice($locale['df_422']);
						$this->addHelperText($$this->field_config['id'], $locale['df_422']);
						break;
					case 4: // File not uploaded
						$this->addNotice($locale['df_423']);
						$this->addHelperText($$this->field_config['id'], $locale['df_423']);
						break;
				}
			} else {
				return $upload;
			}
		} else {
			return array();
		}
	}


	/**
	 * Checks whether $_POST contains token.
	 * @param bool $debug
	 */
	public function sniff_token($debug=FALSE) {
		global $locale;
		if (!empty($_POST)) {
			if (!isset($_POST['fusion_token'])) {
				$this->stop();
				$this->addNotice($locale['token_error_2']);
				if ($debug) print_p($locale['token_error_2']);
			} else {
				// check token.
				if (isset($_POST['token_rings']) && !empty($_POST['token_rings'])) {
					foreach ($_POST['token_rings'] as $hash => $form_name) {
						self::verify_tokens($form_name, 0);
					}
				} else {
					// token tampered
					$this->stop();
					$this->addNotice($locale['token_error_2']);
					if ($debug) print_p($locale['token_error_2']." Tampered.");
				}
			}
		}
	}


	public static function generate_token($form, $max_tokens = 10, $return_token = FALSE) {
		global $userdata, $defender;

		$user_id = defender::set_sessionUserID();
		if ($user_id == 0)  $max_tokens = 1;

		$being_posted = 0;
		if (isset($_POST['token_rings']) && count($_POST['token_rings'])) {
			foreach ($_POST['token_rings'] as $rings => $form_name) {
				if ($form_name == $form) {
					$being_posted = 1;
				}
			}
		}

		if (isset($_POST['fusion_token']) && $being_posted && $defender::verify_tokens($form, $max_tokens)) { // will delete max token out. hence flush out previous token..
			$token = stripinput($_POST['fusion_token']);
			// remove the token from the array as it has been used
			if ($max_tokens > 0) { // token with $post_time 0 are reusable
				foreach ($_SESSION['csrf_tokens'][$form] as $key => $val) {
					if (isset($_POST['fusion_token']) && $val == $_POST['fusion_token']) {
						// consume a token
						unset($_SESSION['csrf_tokens'][$form][$key]);
						// generate a new token to replenish the used one
						$token_time = time();
						$algo = fusion_get_settings('password_algorithm');
						$key = $user_id.$token_time.$form.SECRET_KEY;
						$salt = md5(isset($userdata['user_salt']) ? $userdata['user_salt'].SECRET_KEY_SALT : SECRET_KEY_SALT);
						// generate a new token and store it
						$token = $user_id.".".$token_time.".".hash_hmac($algo, $key, $salt);
						$_SESSION['csrf_tokens'][$form][] = $token;
					}
				}
			}
		} else {
			$token_time = time();
			$algo = fusion_get_settings('password_algorithm');
			$key = $user_id.$token_time.$form.SECRET_KEY;
			$salt = md5(isset($userdata['user_salt']) ? $userdata['user_salt'].SECRET_KEY_SALT : SECRET_KEY_SALT);
			// generate a new token and store it
			$token = $user_id.".".$token_time.".".hash_hmac($algo, $key, $salt);
			// generate a new token.
			$_SESSION['csrf_tokens'][$form][] = $token;
			// store just one token for each form, if the user is a guest
			//print_p("Max token allowed in $form is $max_tokens");
			if ($max_tokens > 0 && count($_SESSION['csrf_tokens'][$form]) > $max_tokens) {
				array_shift($_SESSION['csrf_tokens'][$form]); // remove first element - this keeps changing
			}
			//print_p("And we have ".count($_SESSION['csrf_tokens'][$form])." tokens in place...");
		}

		$shuffle = str_shuffle("abcdefghijklmnopqrstuvwxyz1234567890");
		if ($return_token == 1) {
			$token = stripinput($_POST['fusion_token']);
			return $token;
		} else {
			if (!defined("token-$shuffle")) {
				define("token-$shuffle", TRUE);
				$html = "<input type='hidden' name='fusion_token' value='".$token."' />\n";
				$html .= "<input type='hidden' name='token_rings[$shuffle]' value='".$form."' />\n";
				return $html;
			}
		}
	}

	/**
	 * Token Validation
	 * @param     $form - formname
	 * @param int $post_time - 10 for 10 tokens
	 * @param int $debug - 1 for debug notices
	 * @return bool
	 */
	public static function verify_tokens($form, $post_time = 10, $debug = false) {
		global $locale, $userdata;
		$error = array();
		// used by sniff token, and then used by form itself.
		$user_id = self::set_sessionUserID();
		$algo = fusion_get_settings('password_algorithm');
		$salt = md5(isset($userdata['user_salt']) && !isset($_POST['login']) ? $userdata['user_salt'].SECRET_KEY_SALT : SECRET_KEY_SALT);
		if ($debug) {
			print_p($_POST);
		}
		// check if a session is started
		if (!isset($_SESSION['csrf_tokens'])) {
			$error = $locale['token_error_1'];
			self::stop($locale['token_error_1'], $debug ? 1 : 0);
			// check if a token is posted
		} elseif (!isset($_POST['fusion_token'])) {
			$error = $locale['token_error_2'];
			self::stop($locale['token_error_2'], $debug ? 1 : 0);
			// check if the posted token exists
		} elseif (!in_array($_POST['fusion_token'], isset($_SESSION['csrf_tokens'][$form]) ? $_SESSION['csrf_tokens'][$form] : array())) {
			$error = $locale['token_error_3'];
			self::stop($locale['token_error_3'], $debug ? 1 : 0);
			// invalid token - will not accept double posting.
		} else {
			$token_data = explode(".", stripinput($_POST['fusion_token']));
			// check if the token has the correct format
			if (count($token_data) == 3) {
				list($tuser_id, $token_time, $hash) = $token_data;
				if ($tuser_id != $user_id) { // check if the logged user has the same ID as the one in token
					$error = $locale['token_error_4'];
					self::stop($locale['token_error_4'], $debug ? 1 : 0);
				} elseif (!isnum($token_time)) { // make sure the token datestamp is a number before performing calculations
					$error = $locale['token_error_5'];
					self::stop($locale['token_error_5'], $debug ? 1 : 0);
					// token is not a number.
				} elseif (time()-$token_time < $post_time) { // post made too fast. Set $post_time to 0 for instant. Go for System Settings later.
					$error = $locale['token_error_6'];
					self::stop($locale['token_error_6'], $debug ? 1 : 0);
					// check if the hash in token is valid
				} elseif ($hash != hash_hmac($algo, $user_id.$token_time.$form.SECRET_KEY, $salt)) {
					$error = $locale['token_error_7'];
					self::stop($locale['token_error_7'], $debug ? 1 : 0);
				}
			} else {
				// token incorrect format.
				$error = $locale['token_error_8'];
				self::stop($locale['token_error_8'], $debug ? 1 : 0);
			}
		}
		if ($error) {
			if ($debug) print_p($error);
			return false;
		} else {
			if ($debug) notify("Token Verification Success!", "The token on token ring has been passed and validated successfully.", array('icon' => 'notify_icon n-magic'));
		}
		return true;
		}
	}

function form_sanitizer($value, $default = "", $input_name = FALSE, $multilang = FALSE) {
	global $defender, $locale;
	if ($input_name) {
		$val = array();
		if ($multilang) {
			$main_field_name = ''; $main_field_id = '';
			// copy the first available value to the next one.
			foreach (fusion_get_enabled_languages() as $lang => $language) {
				$iname = $input_name."[".$lang."]";
				if (isset($_SESSION['form_fields'][defender::set_sessionUserID()][$_SERVER['PHP_SELF']][$iname])) {
					$defender->field_config = $_SESSION['form_fields'][defender::set_sessionUserID()][$_SERVER['PHP_SELF']][$iname];
					if ($lang == LANGUAGE) {
						$main_field_name = $defender->field_config['title'];
						$main_field_id = $defender->field_config['id'];
					}
					$defender->field_name = $iname;
					$defender->field_value = $value[$lang];
					$defender->field_default = $default;
					$val[$lang] = $defender->defender();
				}
			}
			if ($defender->field_config['required'] == 1 && (!$value[LANGUAGE])) {
				$helper_text = $defender->field_config['error_text'] ? : sprintf($locale['df_error_text'], $main_field_name);
						$defender->stop();
				$defender->addError($main_field_id);
				$defender->addHelperText($main_field_id, $helper_text);
						$defender->addNotice($helper_text);
					} else {
				foreach($val as $lang => $value) {
					if (empty($value)) {
						$val[$lang] = $val[LANGUAGE];
					}
				}
				return serialize($val);
			}
		} else {
			if (isset($_SESSION['form_fields'][defender::set_sessionUserID()][$_SERVER['PHP_SELF']][$input_name])) {
				$defender->field_config = $_SESSION['form_fields'][defender::set_sessionUserID()][$_SERVER['PHP_SELF']][$input_name];
				$defender->field_name = $input_name;
				$defender->field_value = $value;
				$defender->field_default = $default;
				if ($defender->field_config['required'] == 1 && (!$value)) { // it is required field but does not contain any value.. do reject.
					$helper_text = $defender->field_config['error_text'] ? : sprintf($locale['df_error_text'], $defender->field_config['title']);
					$defender->stop();
					$defender->addError($defender->field_config['id']);
					$defender->addHelperText($defender->field_config['id'], $helper_text);
					$defender->addNotice($helper_text);
				} else {
					return $defender->defender();
				}
			}
		}
	} else {
		// returns descript, sanitized value.
		if ($value) {
			if (!is_array($value)) {
				if (intval($value)) {
					return stripinput($value); // numbers
				} else {
					return stripinput(trim(preg_replace("/ +/i", " ", censorwords($value))));
				}
			} else {
				// flatten array;
				$secured = array();
				foreach($value as $arr=>$unsecured) {
					if (intval($unsecured)) {
						$secured[] = stripinput($unsecured); // numbers
					} else {
						$secured[] = stripinput(trim(preg_replace("/ +/i", " ", censorwords($unsecured))));
					}
				}
				// might want to serialize output in the future if $_POST is an array
				// return addslash(serialize($secured));
				return implode('.', $secured); // this is very different than defender's output, which is based on '|' delimiter
			}
		} else {
			return $default;
		}
	}

	// Amend the only broken element to be found.
	// This is left here to detect $_SESSION exist, not crash everything.
	if ($value !=="" && $value !== NULL) {
		throw new \Exception('The form sanitizer could not handle the request! (input: '.$input_name.')');
	}

}

function sanitize_array($array) {
	foreach ($array as $name => $value) {
		$array[stripinput($name)] = trim(censorwords(stripinput($value)));
	}
	return $array;
}
