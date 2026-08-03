<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Exam;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $posts = BlogPost::published()->select('slug', 'updated_at')->latest('published_at')->get();
        $categories = BlogCategory::where('is_active', true)->select('slug', 'updated_at')->get();
        $exams = Exam::where('is_active', true)->select('slug', 'updated_at')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Static pages
        $staticPages = [
            ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => route('reviewers'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => route('pricing'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => route('blog.index'), 'priority' => '0.8', 'changefreq' => 'daily'],
        ];

        foreach ($staticPages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($page['url']) . '</loc>';
            $xml .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
            $xml .= '<priority>' . $page['priority'] . '</priority>';
            $xml .= '</url>';
        }

        // Exam reviewer pages
        foreach ($exams as $exam) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars(route('reviewer.show', $exam)) . '</loc>';
            $xml .= '<lastmod>' . $exam->updated_at->toISOString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        // Blog category pages
        foreach ($categories as $cat) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars(route('blog.category', $cat)) . '</loc>';
            $xml .= '<lastmod>' . $cat->updated_at->toISOString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '</url>';
        }

        // Blog posts
        foreach ($posts as $post) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars(route('blog.show', $post)) . '</loc>';
            $xml .= '<lastmod>' . $post->updated_at->toISOString() . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
