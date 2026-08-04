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

            <form id="post-thread-form" method="POST" action="{{ route('forum.store', $category) }}" class="card flat-card p-6 sm:p-8 space-y-6">
                @csrf

                {{-- Category Selector --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">
                        Select Category <span class="text-rose-500">*</span>
                    </label>

                    {{-- Hidden Category ID input --}}
                    <input type="hidden" name="category_id" id="category_id_input" value="{{ old('category_id', $category->id) }}">

                    {{-- Category Pills --}}
                    <div class="flex flex-wrap gap-2.5">
                        @foreach($categories as $cat)
                        <button type="button"
                            data-cat-id="{{ $cat->id }}"
                            data-cat-name="{{ $cat->name }}"
                            onclick="selectCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                            class="cat-pill inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold transition-all shadow-sm cursor-pointer {{ (old('category_id', $category->id) == $cat->id) ? 'bg-blue-600 text-white border-blue-600 shadow-blue-500/20' : 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500' }}">
                            <i class="{{ $cat->icon }} text-xs"></i>
                            <span>{{ $cat->name }}</span>
                        </button>
                        @endforeach
                    </div>
                    @error('category_id') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                {{-- Thread Title --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1.5">
                        Discussion Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" id="thread_title" value="{{ old('title') }}" required minlength="5" maxlength="255"
                        placeholder="e.g. Tips for solving Civil Service Math problems fast?"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <p id="title-error-msg" class="hidden text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i> Title must be at least 5 characters long.
                    </p>
                    @error('title') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                {{-- Message Body --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1.5">
                        Discussion Details <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="body" id="thread_body" rows="8" required minlength="10" maxlength="10000"
                        placeholder="Share your question, study insight, or review topic. Be descriptive so fellow examinees can help you effectively."
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('body') }}</textarea>
                    <p id="body-error-msg" class="hidden text-rose-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i> Discussion details must be at least 10 characters long.
                    </p>
                    @error('body') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-info text-[10px]"></i> Minimum 10 characters. Please follow our community rules.
                    </p>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="validateAndConfirm()" class="bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white text-sm font-bold px-7 py-3 rounded-xl transition shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Post Discussion
                    </button>
                    <a href="{{ route('forum.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition px-3 py-2">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </section>

    {{-- Validation Warning Modal --}}
    <div id="warning-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-sm p-6 border border-slate-200 dark:border-slate-700 animate-in fade-in zoom-in duration-200 text-center">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2" id="warning-title">
                Attention Required
            </h3>
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-6" id="warning-message">
                Please complete all required fields.
            </p>

            <button type="button" onclick="closeWarningModal()" class="w-full bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold py-2.5 rounded-xl transition shadow-sm">
                Got it, let me fix it
            </button>
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirm-post-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md p-6 border border-slate-200 dark:border-slate-700 animate-in fade-in zoom-in duration-200">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl mb-4">
                <i class="fa-solid fa-paper-plane"></i>
            </div>

            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                Publish Discussion?
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                Please double-check your post details before publishing to the community.
            </p>

            {{-- Post Preview Box --}}
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-xs space-y-2 mb-6">
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Category:</span>
                    <span id="modal-category-preview" class="font-bold text-blue-600 dark:text-blue-400">{{ $category->name }}</span>
                </div>
                <div class="border-t border-slate-200 dark:border-slate-700 pt-2">
                    <span class="text-slate-400 block mb-0.5">Title:</span>
                    <p id="modal-title-preview" class="font-semibold text-slate-900 dark:text-white line-clamp-2">—</p>
                </div>
            </div>

            {{-- Modal Action Buttons --}}
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="closeConfirmationModal()" class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold px-4 py-2.5 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                    Go Back & Edit
                </button>
                <button type="button" onclick="submitPostForm()" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                    Yes, Publish Now
                </button>
            </div>
        </div>
    </div>

    {{-- Interactive Scripts --}}
    <script>
        let selectedCatName = @json($category->name);

        function selectCategory(id, name) {
            document.getElementById('category_id_input').value = id;
            selectedCatName = name;

            document.querySelectorAll('.cat-pill').forEach(btn => {
                const isSelected = (btn.getAttribute('data-cat-id') == id);
                if (isSelected) {
                    btn.className = 'cat-pill inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold transition-all shadow-sm cursor-pointer bg-blue-600 text-white border-blue-600 shadow-blue-500/20';
                } else {
                    btn.className = 'cat-pill inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold transition-all shadow-sm cursor-pointer bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500';
                }
            });
        }

        function validateAndConfirm() {
            const titleInput = document.getElementById('thread_title');
            const bodyInput = document.getElementById('thread_body');
            const titleErr = document.getElementById('title-error-msg');
            const bodyErr = document.getElementById('body-error-msg');

            const title = titleInput.value.trim();
            const body = bodyInput.value.trim();

            let isValid = true;

            // Title validation
            if (!title || title.length < 5) {
                titleInput.classList.add('border-rose-500', 'ring-1', 'ring-rose-500');
                titleErr.classList.remove('hidden');
                isValid = false;
            } else {
                titleInput.classList.remove('border-rose-500', 'ring-1', 'ring-rose-500');
                titleErr.classList.add('hidden');
            }

            // Body validation
            if (!body || body.length < 10) {
                bodyInput.classList.add('border-rose-500', 'ring-1', 'ring-rose-500');
                bodyErr.classList.remove('hidden');
                isValid = false;
            } else {
                bodyInput.classList.remove('border-rose-500', 'ring-1', 'ring-rose-500');
                bodyErr.classList.add('hidden');
            }

            if (!isValid) {
                if (!title || title.length < 5) {
                    showWarningModal('Invalid Title', 'Please enter a discussion title with at least 5 characters.');
                    titleInput.focus();
                } else if (!body || body.length < 10) {
                    showWarningModal('Details Too Short', 'Please enter discussion details with at least 10 characters.');
                    bodyInput.focus();
                }
                return;
            }

            // Set modal preview details
            document.getElementById('modal-category-preview').innerText = selectedCatName;
            document.getElementById('modal-title-preview').innerText = title;

            // Show confirmation modal
            document.getElementById('confirm-post-modal').classList.remove('hidden');
        }

        function showWarningModal(title, message) {
            document.getElementById('warning-title').innerText = title;
            document.getElementById('warning-message').innerText = message;
            document.getElementById('warning-modal').classList.remove('hidden');
        }

        function closeWarningModal() {
            document.getElementById('warning-modal').classList.add('hidden');
        }

        function closeConfirmationModal() {
            document.getElementById('confirm-post-modal').classList.add('hidden');
        }

        function submitPostForm() {
            document.getElementById('post-thread-form').submit();
        }
    </script>
</x-public-layout>
