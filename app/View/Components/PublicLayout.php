<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PublicLayout extends Component
{
    public string $metaTitle;
    public string $metaDescription;
    public string $metaKeywords;
    public string $canonical;
    public string $ogType;

    public function __construct(
        string $metaTitle = 'ExamReady PH — Free Philippine Exam Reviewer with AI Taglish Explanations',
        string $metaDescription = 'Master UPCAT, Civil Service, LET, and NMAT with 15,000+ updated practice questions.',
        string $metaKeywords = 'Philippine exam reviewer, UPCAT reviewer, civil service reviewer',
        string $canonical = '',
        string $ogType = 'website',
    ) {
        $this->metaTitle = $metaTitle;
        $this->metaDescription = $metaDescription;
        $this->metaKeywords = $metaKeywords;
        $this->canonical = $canonical ?: url()->current();
        $this->ogType = $ogType;
    }

    public function render(): View
    {
        return view('layouts.public');
    }
}
