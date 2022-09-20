<?php
function encrypt($plainText, $key)
{
	$secretKey = hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
	$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
	$plainPad = pkcs5_pad($plainText, $blockSize);
	if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1) {
		$encryptedText = mcrypt_generic($openMode, $plainPad);
		mcrypt_generic_deinit($openMode);
	}
	return bin2hex($encryptedText);
}

function decrypt($encryptedText, $key)
{
	$secretKey = hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$encryptedText = hextobin($encryptedText);
	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
	mcrypt_generic_init($openMode, $secretKey, $initVector);
	$decryptedText = mdecrypt_generic($openMode, $encryptedText);
	$decryptedText = rtrim($decryptedText, "\0");
	mcrypt_generic_deinit($openMode);
	return $decryptedText;
}
//*********** Padding Function *********************

function pkcs5_pad($plainText, $blockSize)
{
	$pad = $blockSize - (strlen($plainText) % $blockSize);
	return $plainText . str_repeat(chr($pad), $pad);
}

//********** Hexadecimal to Binary function for php 4.0 version ********

function hextobin($hexString)
{
	$length = strlen($hexString);
	$binString = "";
	$count = 0;
	while ($count < $length) {
		$subString = substr($hexString, $count, 2);
		$packedString = pack("H*", $subString);
		if ($count == 0) {
			$binString = $packedString;
		} else {
			$binString .= $packedString;
		}

		$count += 2;
	}
	return $binString;
}

function make_input_encrypt($input)
{
	// Store a string into the variable which
	// need to be Encrypted
	$simple_string = $input;

	// Store the cipher method
	$ciphering = "AES-128-CTR";

	// Use OpenSSl Encryption method
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;

	// Non-NULL Initialization Vector for encryption
	$encryption_iv = '1234567891011121';

	// Store the encryption key
	$encryption_key = $_ENV['ENCRYPT_KEY'];

	// Use openssl_encrypt() function to encrypt the data
	$encryption = openssl_encrypt(
		$simple_string,
		$ciphering,
		$encryption_key,
		$options,
		$encryption_iv
	);

	return $encryption;
}


function make_input_decrypt($input)
{
	// Non-NULL Initialization Vector for decryption
	$decryption_iv = '1234567891011121';

	// Store the decryption key
	$decryption_key = $_ENV['ENCRYPT_KEY'];

	$ciphering = "AES-128-CTR";

	$options = 0;

	// Use openssl_decrypt() function to decrypt the data
	$decryption = openssl_decrypt(
		$input,
		$ciphering,
		$decryption_key,
		$options,
		$decryption_iv
	);

	// Display the decrypted string
	return $decryption;
}
