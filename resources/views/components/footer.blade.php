{{-- Footer --}}
<footer class="bg-slate-900 dark:bg-slate-950 text-slate-400 py-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-slate-800">

            {{-- Brand Column --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <span class="text-lg font-extrabold text-white">ExamReady <span class="text-blue-500">PH</span></span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Empowering Filipino examinees nationwide with free, AI-backed review tools for college entrance and professional licensure exams.
                </p>
                <div class="flex gap-3 text-slate-400 text-base">
                    <a href="#" class="hover:text-blue-400 transition" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="hover:text-blue-400 transition" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="#" class="hover:text-blue-400 transition" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#" class="hover:text-blue-400 transition" aria-label="Discord"><i class="fa-brands fa-discord"></i></a>
                </div>
            </div>

            {{-- Reviewers Link Column --}}
            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Popular Reviewers</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('home') }}#categories" class="hover:text-white transition">UPCAT Reviewer 2026</a></li>
                    <li><a href="{{ route('home') }}#categories" class="hover:text-white transition">Civil Service Professional</a></li>
                    <li><a href="{{ route('home') }}#categories" class="hover:text-white transition">Civil Service Subprofessional</a></li>
                    <li><a href="{{ route('home') }}#categories" class="hover:text-white transition">LET Licensure Exam for Teachers</a></li>
                    <li><a href="{{ route('home') }}#categories" class="hover:text-white transition">NMAT Practice Drill</a></li>
                </ul>
            </div>

            {{-- Resources Column --}}
            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Resources</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('home') }}#articles" class="hover:text-white transition">Study Guides</a></li>
                    <li><a href="{{ route('home') }}#faq" class="hover:text-white transition">FAQ</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Pricing</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Forum</a></li>
                </ul>
            </div>

            {{-- Payment & Legal --}}
            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Supported Payments</h4>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1.5 rounded bg-slate-800 border border-slate-700 text-slate-300 font-bold text-xs flex items-center gap-1">
                        <i class="fa-solid fa-wallet text-blue-400"></i> GCash
                    </span>
                    <span class="px-3 py-1.5 rounded bg-slate-800 border border-slate-700 text-slate-300 font-bold text-xs flex items-center gap-1">
                        <i class="fa-solid fa-credit-card text-emerald-400"></i> Maya
                    </span>
                </div>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Privacy Policy</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Terms of Service</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Refund Policy</a></li>
                </ul>
            </div>

        </div>

        {{-- Disclaimer --}}
        <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-[11px] text-slate-400">
            <p>© {{ date('Y') }} ExamReady PH. All rights reserved.</p>
            <p class="text-center md:text-right max-w-xl">
                <span class="font-semibold text-slate-300">Disclaimer:</span> ExamReady PH is an independent educational platform and is NOT officially affiliated with or endorsed by the Professional Regulation Commission (PRC), Civil Service Commission (CSC), or the University of the Philippines (UP).
            </p>
        </div>
    </div>
</footer>
