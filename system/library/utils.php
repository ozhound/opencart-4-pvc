<?php
namespace Begateway;

class Utils
{
	/**
	 * get the module version from install.json
	 * @return string
	 */
	public static function getModuleVersion(bool $addPrefixV = true): string
	{
		$version = '';

		$json = file_get_contents(DIR_EXTENSION . 'begateway/install.json');
		if ($json) {
			$data = json_decode($json, true);

			if (isset($data['version'])) {

				if ($addPrefixV) {
					$version = 'v';
				}

				$version .= $data['version'];
			}
		}

		return $version;
	}

	/**
	 * get the flash message from session and return it as an associative array
	 * $sessionData references the session data in opencart $this->session->data
	 * [type => 'error' | 'success', text => 'message']
	 * @return array
	 */
	public static function getFlashMessageAssocArray(&$sessionData): array
	{
		$message = [];

		if (isset($sessionData['error'])) {
			$message['type'] = 'danger';
			$message['text'] = $sessionData['error'];

			unset($sessionData['error']);
		}

		if (isset($sessionData['success'])) {
			$message['type'] = 'success';
			$message['text'] = $sessionData['success'];

			unset($sessionData['success']);
		}

		return $message;
	}
}
