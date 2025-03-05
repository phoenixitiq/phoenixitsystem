<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\SecurityScan;
use App\Models\Vulnerability;
use App\Models\WebsiteAnalysis;
use Illuminate\Support\Facades\Log;

class SecurityScanService
{
    protected $config;
    protected $apis = [
        'virustotal' => 'https://www.virustotal.com/vtapi/v2/',
        'securitytrails' => 'https://api.securitytrails.com/v1/',
        'shodan' => 'https://api.shodan.io/'
    ];

    public function __construct()
    {
        $this->config = config('security');
    }

    public function scanWebsite($url)
    {
        try {
            $scan = SecurityScan::create([
                'url' => $url,
                'status' => 'scanning'
            ]);

            // فحص SSL/TLS
            $sslInfo = $this->checkSSL($url);
            
            // فحص الثغرات المعروفة
            $vulnerabilities = $this->scanVulnerabilities($url);
            
            // فحص التكوين الأمني
            $securityHeaders = $this->checkSecurityHeaders($url);
            
            // فحص محتوى ضار
            $malwareCheck = $this->scanForMalware($url);

            $scan->update([
                'ssl_info' => $sslInfo,
                'vulnerabilities' => $vulnerabilities,
                'security_headers' => $securityHeaders,
                'malware_status' => $malwareCheck,
                'status' => 'completed',
                'completed_at' => now()
            ]);

            return $scan;
        } catch (\Exception $e) {
            Log::error("Security scan failed for {$url}: " . $e->getMessage());
            throw $e;
        }
    }

    public function analyzeSocialMedia($platform, $account)
    {
        try {
            // تحليل الحساب
            $profileAnalysis = $this->analyzeProfile($platform, $account);
            
            // فحص التفاعلات المشبوهة
            $suspiciousActivity = $this->detectSuspiciousActivity($platform, $account);
            
            // تحليل الروابط المنشورة
            $linksAnalysis = $this->analyzePosts($platform, $account);

            return [
                'profile_security' => $profileAnalysis,
                'suspicious_activity' => $suspiciousActivity,
                'links_analysis' => $linksAnalysis,
                'recommendations' => $this->generateSecurityRecommendations($platform)
            ];
        } catch (\Exception $e) {
            Log::error("Social media analysis failed for {$platform}/{$account}: " . $e->getMessage());
            throw $e;
        }
    }

    public function performPenetrationTest($target)
    {
        try {
            // فحص المنافذ المفتوحة
            $openPorts = $this->scanPorts($target);
            
            // فحص الخدمات
            $services = $this->detectServices($target);
            
            // فحص نقاط الضعف
            $weaknesses = $this->findWeaknesses($target);

            return [
                'open_ports' => $openPorts,
                'running_services' => $services,
                'vulnerabilities' => $weaknesses,
                'risk_assessment' => $this->assessRisks($target)
            ];
        } catch (\Exception $e) {
            Log::error("Penetration test failed for {$target}: " . $e->getMessage());
            throw $e;
        }
    }

    public function monitorSecurityStatus($target)
    {
        return [
            'uptime' => $this->checkUptime($target),
            'ssl_status' => $this->monitorSSL($target),
            'security_score' => $this->calculateSecurityScore($target),
            'recent_incidents' => $this->getSecurityIncidents($target),
            'recommendations' => $this->getSecurityRecommendations($target)
        ];
    }

    protected function checkSSL($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $response = curl_exec($ch);
        $certInfo = curl_getinfo($ch, CURLINFO_SSL_VERIFYRESULT);
        curl_close($ch);

        return [
            'valid' => $certInfo === 0,
            'details' => openssl_x509_parse($response),
            'expiry' => $this->getSSLExpiry($url)
        ];
    }

    protected function scanVulnerabilities($url)
    {
        $vulnerabilities = [];
        
        // فحص XSS
        $vulnerabilities['xss'] = $this->checkXSS($url);
        
        // فحص SQL Injection
        $vulnerabilities['sql_injection'] = $this->checkSQLInjection($url);
        
        // فحص CSRF
        $vulnerabilities['csrf'] = $this->checkCSRF($url);
        
        // فحص File Inclusion
        $vulnerabilities['file_inclusion'] = $this->checkFileInclusion($url);

        return $vulnerabilities;
    }

    protected function checkSecurityHeaders($url)
    {
        $headers = get_headers($url, 1);
        
        return [
            'x_frame_options' => $headers['X-Frame-Options'] ?? null,
            'x_xss_protection' => $headers['X-XSS-Protection'] ?? null,
            'content_security_policy' => $headers['Content-Security-Policy'] ?? null,
            'strict_transport_security' => $headers['Strict-Transport-Security'] ?? null
        ];
    }

    protected function scanForMalware($url)
    {
        // استخدام API من VirusTotal
        $response = Http::get($this->apis['virustotal'] . 'url/report', [
            'apikey' => $this->config['virustotal_api_key'],
            'resource' => $url
        ]);

        return [
            'is_malicious' => $response['positives'] > 0,
            'scan_results' => $response['scans'],
            'last_scan' => $response['scan_date']
        ];
    }

    protected function analyzeProfile($platform, $account)
    {
        // تحليل إعدادات الخصوصية
        $privacySettings = $this->checkPrivacySettings($platform, $account);
        
        // تحليل المتابعين
        $followersAnalysis = $this->analyzeFollowers($platform, $account);
        
        // تحليل النشاط
        $activityAnalysis = $this->analyzeActivity($platform, $account);

        return [
            'privacy_score' => $privacySettings,
            'followers_authenticity' => $followersAnalysis,
            'activity_patterns' => $activityAnalysis
        ];
    }

    protected function detectSuspiciousActivity($platform, $account)
    {
        return [
            'bot_activity' => $this->detectBots($platform, $account),
            'spam_patterns' => $this->detectSpam($platform, $account),
            'unusual_behavior' => $this->detectUnusualBehavior($platform, $account)
        ];
    }

    // ... المزيد من الوظائف المساعدة ...
} 