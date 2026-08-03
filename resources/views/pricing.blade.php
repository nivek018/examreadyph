<x-public-layout
    metaTitle="Pricing & Subscription Plans — ExamReady PH"
    metaDescription="Affordable Philippine reviewer subscription plans for Civil Service, LET, and College Entrance Exams. Upgrade to Pro for 100% ad-free practice tests and AI Taglish tutor access."
>
    {{-- Pricing Hero --}}
    <section class="py-16 bg-gradient-to-b from-blue-50/50 to-white dark:from-slate-900 dark:to-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="badge-blue uppercase text-xs font-bold px-3 py-1 mb-4 inline-block">Simple & Affordable Pricing</span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-4">
                Invest in Your Future. <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-teal-500">Pass Your Board or Civil Service Exam.</span>
            </h1>
            <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
                Start with free practice questions or upgrade to Pro for 100% ad-free review, unlimited questions, and AI Taglish explanations.
            </p>
        </div>
    </section>

    {{-- Pricing Cards Grid --}}
    <section class="pb-16 -mt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                @foreach($plans as $plan)
                @php
                    $isPopular = $plan->slug === 'pro-monthly';
                    $isVip = $plan->slug === 'vip-annual';
                @endphp
                <div class="card p-8 flex flex-col justify-between relative transition-all duration-300 hover:shadow-xl
                    {{ $isPopular ? 'border-2 border-blue-500 ring-4 ring-blue-500/10 dark:bg-slate-900 scale-105 z-10' : ($isVip ? 'border-2 border-purple-500/80 dark:bg-slate-900' : 'bg-white dark:bg-slate-900') }}">

                    @if($isPopular)
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-blue-600 text-white text-[11px] font-extrabold uppercase tracking-wider px-3 py-1 rounded-full shadow-md">
                        <i class="fa-solid fa-fire mr-1"></i> Most Popular Choice
                    </div>
                    @elseif($isVip)
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-purple-600 text-white text-[11px] font-extrabold uppercase tracking-wider px-3 py-1 rounded-full shadow-md">
                        <i class="fa-solid fa-crown mr-1"></i> Best Value (Save 70%)
                    </div>
                    @endif

                    <div>
                        {{-- Header --}}
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ $plan->name }}</h3>
                            <span class="{{ $plan->badge_color ?? 'badge-blue' }} text-[11px]">{{ $plan->duration_days >= 365 ? '1 Year' : ($plan->duration_days === 30 ? '30 Days' : 'Forever') }}</span>
                        </div>

                        {{-- Price --}}
                        <div class="mb-6">
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white">₱{{ number_format($plan->price, 0) }}</span>
                                <span class="text-slate-500 text-sm font-medium">/ {{ $plan->duration_days >= 365 ? 'year' : ($plan->duration_days === 30 ? 'month' : 'one-time') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">
                                @if($plan->price == 0)
                                Free forever. No credit card required.
                                @elseif($plan->duration_days == 30)
                                Less than ₱5 per day. Cancel anytime.
                                @else
                                Only ₱33/month equivalent!
                                @endif
                            </p>
                        </div>

                        {{-- Features List --}}
                        <div class="space-y-3 mb-8">
                            @foreach($plan->features_json as $feature)
                            <div class="flex items-start gap-3 text-xs sm:text-sm text-slate-700 dark:text-slate-300">
                                <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0"></i>
                                <span>{{ $feature }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div>
                        @if($plan->price == 0)
                            <a href="{{ route('register') }}" class="w-full btn-brand-outline py-3 text-sm text-center block">
                                Get Started Free
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="w-full btn-brand py-3 text-sm text-center block shadow-lg shadow-blue-500/20">
                                <i class="fa-solid fa-bolt mr-1"></i> Subscribe Now
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Payment Methods Banner --}}
    <section class="py-10 bg-slate-50 dark:bg-slate-900 border-y border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Supported Philippine Payment Gateways</h4>
            <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-12 opacity-80">
                <div class="flex items-center gap-2 font-bold text-slate-700 dark:text-slate-300 text-sm">
                    <span class="w-8 h-8 rounded-lg bg-blue-500 text-white flex items-center justify-center font-black text-xs">G</span> GCash
                </div>
                <div class="flex items-center gap-2 font-bold text-slate-700 dark:text-slate-300 text-sm">
                    <span class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-black text-xs">M</span> Maya (PayMaya)
                </div>
                <div class="flex items-center gap-2 font-bold text-slate-700 dark:text-slate-300 text-sm">
                    <span class="w-8 h-8 rounded-lg bg-teal-600 text-white flex items-center justify-center font-black text-xs">QR</span> QR Ph
                </div>
                <div class="flex items-center gap-2 font-bold text-slate-700 dark:text-slate-300 text-sm">
                    <span class="w-8 h-8 rounded-lg bg-purple-600 text-white flex items-center justify-center font-black text-xs">P</span> PrepayPH (No DTI/BIR required)
                </div>
            </div>
        </div>
    </section>

    {{-- Subscription FAQ --}}
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Frequently Asked Questions</h2>
                <p class="text-sm text-slate-500 mt-2">Have questions about our plans? Here are answers to common questions.</p>
            </div>

            <div class="space-y-4">
                <div class="card p-5">
                    <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2">How do I access my reviewer after paying?</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Once your payment is completed via GCash or Maya, your account is automatically upgraded instantly! You can immediately start taking all premium mock tests, practice questions, and asking AI explanations right on the website.</p>
                </div>
                <div class="card p-5">
                    <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2">Do you send PDF files after payment?</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">No. ExamReady PH is an interactive online review platform — not a static PDF file. You use our smart exam engine directly on your phone or laptop with dynamic timers, auto-scoring, weak-area analytics, and AI Taglish explanations.</p>
                </div>
                <div class="card p-5">
                    <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2">Can I use one account on multiple devices?</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Yes! You can log in with your account on your desktop, smartphone, or tablet. Your exam progress and AI conversation history will sync seamlessly.</p>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
