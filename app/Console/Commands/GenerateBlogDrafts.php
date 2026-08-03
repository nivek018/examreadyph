<?php

namespace App\Console\Commands;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateBlogDrafts extends Command
{
    protected $signature = 'blog:generate-drafts {--exam=Civil Service Exam}';
    protected $description = 'Generate SEO draft blog posts from template configurations';

    public function handle(): int
    {
        $examName = $this->option('exam');
        $jsonPath = database_path('data/seo_templates.json');

        if (!file_exists($jsonPath)) {
            $this->error('Templates file not found at database/data/seo_templates.json');
            return Command::FAILURE;
        }

        $templates = json_decode(file_get_contents($jsonPath), true);
        if (!$templates) {
            $this->error('Failed to parse json templates.');
            return Command::FAILURE;
        }

        $adminUser = User::where('role', 'admin')->first() ?? User::first();
        if (!$adminUser) {
            $this->error('No admin user found to assign as post author.');
            return Command::FAILURE;
        }

        $createdCount = 0;

        foreach ($templates as $tpl) {
            $title = str_replace('{exam}', $examName, $tpl['title_template']);
            $slug = Str::slug($title);

            if (BlogPost::where('slug', $slug)->exists()) {
                $this->info("Skipping existing post: {$title}");
                continue;
            }

            $category = BlogCategory::where('slug', $tpl['category_slug'])->first()
                ?? BlogCategory::first();

            $body = str_replace('{exam}', $examName, $tpl['body_template']);
            $excerpt = str_replace('{exam}', $examName, $tpl['excerpt_template']);

            $post = BlogPost::create([
                'category_id' => $category?->id,
                'author_id' => $adminUser->id,
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $excerpt,
                'body' => $body,
                'seo_title' => $title . ' — ExamReady PH',
                'seo_description' => $excerpt,
                'status' => 'draft',
                'is_featured' => false,
            ]);

            if (!empty($tpl['tags'])) {
                $tagIds = [];
                foreach ($tpl['tags'] as $tagName) {
                    $tag = BlogTag::firstOrCreate(
                        ['slug' => Str::slug($tagName)],
                        ['name' => $tagName]
                    );
                    $tagIds[] = $tag->id;
                }
                $post->tags()->sync($tagIds);
            }

            $createdCount++;
            $this->info("Generated draft: {$title}");
        }

        $this->info("Successfully generated {$createdCount} blog drafts.");
        return Command::SUCCESS;
    }
}
