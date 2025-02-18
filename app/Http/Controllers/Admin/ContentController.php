<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function pageBuilder($pageId)
    {
        $page = Page::findOrFail($pageId);
        $currentLocale = app()->getLocale();
        
        return view('admin.content.page-builder', compact('page', 'currentLocale'));
    }

    public function savePage(Request $request, $pageId)
    {
        $page = Page::findOrFail($pageId);
        
        PageTranslation::updateOrCreate(
            [
                'page_id' => $pageId,
                'locale' => $request->locale
            ],
            [
                'content' => $request->content
            ]
        );

        return response()->json(['message' => 'تم الحفظ بنجاح']);
    }

    public function getPageContent($pageId, $locale)
    {
        $page = Page::findOrFail($pageId);
        $translation = $page->translations()->where('locale', $locale)->first();
        
        return response()->json([
            'content' => $translation ? $translation->content : ''
        ]);
    }
} 