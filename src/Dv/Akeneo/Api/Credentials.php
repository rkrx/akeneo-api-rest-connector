<?php
namespace Dv\Akeneo\Api;

use DateTimeImmutable;

class Credentials {
	/**
	 * @var string
	 */
	private $username = null;

	/**
	 * @var string
	 */
	private $key = null;

	/**
	 * @var string
	 */
	private $salt = null;

	/**
	 * @var string
	 */
	private $nonce = null;

	/**
	 * @param string $username
	 * @param string $apiKey
	 * @param string $salt
	 */
	public function __construct($username, $apiKey, $salt) {
		$this->username = $username;
		$this->key = $apiKey;
		$this->salt = $salt;
		$this->nonce = uniqid();
	}

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return string
	 */
	public function getNonce() {
		return $this->nonce;
	}

	/**
	 * @param DateTimeImmutable $createdAt
	 * @return string
	 */
	public function getDigest(DateTimeImmutable $createdAt) {
		return base64_encode(sha1(base64_decode($this->nonce) . $createdAt->format('c') . $this->getKeyWithSalt(), true));;
	}

	/**
	 * @return string
	 */
	public function getKeyWithSalt() {
		return "{$this->key}{{$this->salt}}";
	}
}