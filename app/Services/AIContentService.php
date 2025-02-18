<?php

namespace App\Services;

use App\Models\AIContent;
use App\Models\ContentTemplate;
use App\Models\ContentAnalytics;
use OpenAI\OpenAI;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIContentService
{
    protected $openai;
    protected $config;

    public function __construct()
    {
        $this->openai = new OpenAI([
            'api_key' => config('services.openai.api_key')
        ]);
        $this->config = config('ai_content');
    }

    public function generateContent($prompt, $type, $language = 'ar')
    {
        try {
            $template = $this->getContentTemplate($type);
            $enhancedPrompt = $this->enhancePrompt($prompt, $template, $language);
            
            $response = $this->openai->completions()->create([
                'model' => 'gpt-4',
                'prompt' => $enhancedPrompt,
                'max_tokens' => 1000,
                'temperature' => 0.7
            ]);

            $generatedContent = $response['choices'][0]['text'];
            
            return AIContent::create([
                'prompt' => $prompt,
                'type' => $type,
                'language' => $language,
                'content' => $generatedContent,
                'metadata' => [
                    'template_used' => $template->id,
                    'model' => 'gpt-4',
                    'tokens_used' => $response['usage']['total_tokens']
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("AI content generation failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function optimizeContent($content, $platform = null)
    {
        $optimizedContent = $content;

        // تحسين SEO
        $optimizedContent = $this->optimizeForSEO($optimizedContent);

        // تحسين للمنصة المحددة
        if ($platform) {
            $optimizedContent = $this->optimizeForPlatform($optimizedContent, $platform);
        }

        // تحسين القراءة
        $optimizedContent = $this->improveReadability($optimizedContent);

        // تحسين المشاركة
        $optimizedContent = $this->optimizeForEngagement($optimizedContent);

        return $optimizedContent;
    }

    public function generateSocialMediaContent($data)
    {
        try {
            $content = [];
            foreach ($data['platforms'] as $platform) {
                $content[$platform] = [
                    'text' => $this->generatePlatformSpecificContent($data['topic'], $platform),
                    'hashtags' => $this->generateRelevantHashtags($data['topic'], $platform),
                    'media_suggestions' => $this->suggestMedia($data['topic'], $platform),
                    'best_posting_times' => $this->suggestPostingTimes($platform)
                ];
            }

            return $content;
        } catch (\Exception $e) {
            Log::error("Social media content generation failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function generateMarketingCopy($data)
    {
        try {
            return [
                'headlines' => $this->generateHeadlines($data),
                'descriptions' => $this->generateDescriptions($data),
                'call_to_action' => $this->generateCTA($data),
                'ad_variations' => $this->generateAdVariations($data),
                'keywords' => $this->suggestKeywords($data)
            ];
        } catch (\Exception $e) {
            Log::error("Marketing copy generation failed: " . $e->getMessage());
            throw $e;
        }
    }

    protected function enhancePrompt($prompt, $template, $language)
    {
        $enhancedPrompt = $template->base_prompt;
        $enhancedPrompt = str_replace('{USER_PROMPT}', $prompt, $enhancedPrompt);
        $enhancedPrompt = str_replace('{LANGUAGE}', $language, $enhancedPrompt);
        
        // إضافة سياق إضافي
        $enhancedPrompt .= "\n\nTarget Audience: " . $template->target_audience;
        $enhancedPrompt .= "\nTone of Voice: " . $template->tone;
        $enhancedPrompt .= "\nBrand Guidelines: " . $template->brand_guidelines;

        return $enhancedPrompt;
    }

    protected function optimizeForSEO($content)
    {
        // تحليل الكلمات المفتاحية
        $keywords = $this->analyzeKeywords($content);
        
        // تحسين العناوين
        $content = $this->optimizeHeadings($content);
        
        // تحسين الوصف
        $content = $this->optimizeMeta($content);
        
        // تحسين الروابط
        $content = $this->optimizeLinks($content);

        return $content;
    }

    protected function optimizeForPlatform($content, $platform)
    {
        switch ($platform) {
            case 'instagram':
                return $this->optimizeForInstagram($content);
            case 'facebook':
                return $this->optimizeForFacebook($content);
            case 'twitter':
                return $this->optimizeForTwitter($content);
            case 'linkedin':
                return $this->optimizeForLinkedIn($content);
            default:
                return $content;
        }
    }

    protected function generatePlatformSpecificContent($topic, $platform)
    {
        $prompt = $this->buildPlatformPrompt($topic, $platform);
        return $this->generateContent($prompt, 'social_post', 'ar');
    }

    protected function buildPlatformPrompt($topic, $platform)
    {
        $platformSpecifics = [
            'instagram' => [
                'style' => 'visual and engaging',
                'length' => 'concise',
                'features' => 'hashtags, emojis'
            ],
            'facebook' => [
                'style' => 'conversational and informative',
                'length' => 'medium',
                'features' => 'links, rich media'
            ],
            'twitter' => [
                'style' => 'brief and punchy',
                'length' => 'very short',
                'features' => 'hashtags, mentions'
            ],
            'linkedin' => [
                'style' => 'professional and insightful',
                'length' => 'detailed',
                'features' => 'industry insights, statistics'
            ]
        ];

        $specs = $platformSpecifics[$platform];
        return "Create a {$specs['style']} post about {$topic} for {$platform}. " .
               "Keep it {$specs['length']} and include {$specs['features']}.";
    }

    // ... المزيد من الوظائف المساعدة ...
} 