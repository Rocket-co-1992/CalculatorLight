<?php
namespace WebPanel;

class SSLManager {
    private $config;
    private $logger;

    public function __construct() {
        $this->config = require dirname(__DIR__) . '/config/webpanel.php';
        $this->logger = new Logger();
    }

    public function checkCertificate() {
        $domain = $this->config['app']['url'];
        $cert = $this->getCertificateInfo($domain);
        
        if ($cert['daysUntilExpiry'] < 30) {
            $this->logger->log(
                "SSL Certificate expires in {$cert['daysUntilExpiry']} days",
                'warning'
            );
        }

        return $cert;
    }

    private function getCertificateInfo($domain) {
        $domain = parse_url($domain, PHP_URL_HOST);
        $context = stream_context_create(['ssl' => ['capture_peer_cert' => true]]);
        $client = stream_socket_client(
            "ssl://{$domain}:443",
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$client) {
            throw new \Exception("SSL connection failed: {$errstr}");
        }

        $params = stream_context_get_params($client);
        $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);

        return [
            'validFrom' => date('Y-m-d H:i:s', $cert['validFrom_time_t']),
            'validTo' => date('Y-m-d H:i:s', $cert['validTo_time_t']),
            'daysUntilExpiry' => ceil(($cert['validTo_time_t'] - time()) / 86400),
            'issuer' => $cert['issuer']['CN']
        ];
    }
}
