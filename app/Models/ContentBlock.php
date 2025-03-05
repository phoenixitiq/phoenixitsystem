<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ContentBlock extends Model
{
    protected $fillable = [
        'key_name',
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'page',
        'section',
        'is_active'
    ];

    public static function getContent($key, $lang = null)
    {
        if (!$lang) {
            $lang = app()->getLocale();
        }

        return Cache::remember('content_'.$key.'_'.$lang, 3600, function() use ($key, $lang) {
            $block = self::where('key_name', $key)->where('is_active', true)->first();
            if (!$block) return null;

            return [
                'title' => $lang == 'ar' ? $block->title_ar : $block->title_en,
                'content' => $lang == 'ar' ? $block->content_ar : $block->content_en
            ];
        });
    }

    public static function getPageContent($page, $section = null)
    {
        $query = self::where('page', $page)->where('is_active', true);
        if ($section) {
            $query->where('section', $section);
        }
        return $query->get();
    }
} 