<?php
namespace Dv\Akeneo\Api;

use DateTimeImmutable;

class Client {
	/**
	 * @var string
	 */
	private $endPoint = null;

	/**
	 * @var Credentials
	 */
	private $cred = null;

	/**
	 * @param string $endPoint
	 * @param Credentials $cred
	 */
	public function __construct($endPoint, Credentials $cred) {
		$this->endPoint = $endPoint;
		$this->cred = $cred;
	}

	/**
	 * @param DateTimeImmutable $createdAt
	 * @return array
	 */
	public function getHeaders(DateTimeImmutable $createdAt) {
		$headers = array();
		$headers[] = 'CONTENT-TYPE: application/json';
		$headers[] = 'Authorization: WSSE profile="UsernameToken"';

		$username = $this->cred->getUsername();
		$digest = $this->cred->getDigest($createdAt);
		$nonce = $this->cred->getNonce();

		$string = 'X-WSSE: UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"';
		$headers[] = sprintf($string, $username, $digest, $nonce, $createdAt->format('c'));

		return $headers;
	}

	/**
	 * @param string $path
	 * @throws Client\ApiHttpCallException
	 * @return string
	 */
	public function call($path) {
		$createdAt = new DateTimeImmutable();

		$headers = $this->getHeaders($createdAt);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "{$this->endPoint}{$path}");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);

		$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (false === $result) {
			throw new Client\ApiHttpCallException("HTTP Return code: {$httpStatus}", $httpStatus);
		}

		return $result;
	}

	/**
	 * @param string $path
	 * @return array
	 */
	public function callJson($path) {
		$result = $this->call($path);
		$json = json_decode($result, true);
		return $json;
	}
}