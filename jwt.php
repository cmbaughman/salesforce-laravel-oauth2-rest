<?php

$privateKeyPassphrase = null;
$csrString = '';
$privateKeyString = '';
$certString = '';

$config = [
    'private_key_type' => \OPENSSL_KEYTYPE_RSA,
    'digest_alg' => 'sha256',
    'private_key_bits' => 2048,
];

$dn = array(
    "countryName" => "US",
    "stateOrProvinceName" => "New York",
    "localityName" => "New York",
    "organizationName" => "SalesforceLaravel",
    "organizationalUnitName" => "SalesforceLaravel",
    "commonName" => "SalesforceLaravel",
    "emailAddress" => "SalesforceLaravel@example.com"
);

$privateKey = openssl_pkey_new($config);

$csr = openssl_csr_new($dn, $privateKey);

$sscert = openssl_csr_sign($csr, null, $privateKey, 365);

openssl_csr_export($csr, $csrString);
file_put_contents(__DIR__.'/../csr.csr', $csrString);

openssl_x509_export($sscert, $certString);
file_put_contents(__DIR__.'/../public.crt', $certString);

openssl_pkey_export($privateKey, $privateKeyString, $privateKeyPassphrase);
file_put_contents(__DIR__.'/../private.key', $privateKeyString);