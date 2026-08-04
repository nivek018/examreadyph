<x-public-layout
    :metaTitle="'New Discussion — ExamReady PH Community'"
    metaDescription="Start a new discussion in the ExamReady PH community forum."
>
    {{-- Breadcrumb --}}
    <div class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-3">
            <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <a href="{{ route('forum.index') }}" class="hover:text-blue-600 transition">Community</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <span class="text-slate-700 dark:text-slate-300 font-medium">New Discussion</span>
            </nav>
        </div>
    </div>

    <section class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-6">Start a New Discussion</h1>

            <form method="POST" action="{{ route('forum.store', $category) }}" class="card flat-card p-6 sm:p-8 space-y-5">
                @csrf
                <input type="hidden" name="category_id" id="selected_category_id" value="{{ $category->id }}">

                <div>
                    <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">Select Category <span class="text-rose-500">*</span></label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($categories as $cat)
                        <button type="button"
                            onclick="document.getElementById('selected_category_id').value = {{ $cat->id }}; document.querySelectorAll('.cat-pill').forEach(el => el.classList.remove('bg-blue-600', 'text-white', 'border-blue-600')); this.classList.add('bg-blue-600', 'text-white', 'border-blue-600');"
                            class="cat-pill inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border text-xs font-bold transition-all shadow-sm {{ $category->id === $cat->id ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-blue-400' }}">
                            <i class="{{ $cat->icon }} text-xs"></i> {{ $cat->name }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Thread Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required minlength="5" maxlength="255" placeholder="e.g. Tips for solving Civil Service Math problems fast?" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Your Message <span class="text-rose-500">*</span></label>
                    <textarea name="body" rows="8" required minlength="10" maxlength="10000" placeholder="Share your question, insight, or discussion topic. Be descriptive so others can help you effectively." class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('body') }}</textarea>
                    @error('body') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-slate-400 mt-1">Minimum 10 characters. No sharing of actual exam questions or answers.</p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-8 py-3 rounded-xl transition shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Post Discussion
                    </button>
                    <a href="{{ route('forum.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</x-public-layout>
