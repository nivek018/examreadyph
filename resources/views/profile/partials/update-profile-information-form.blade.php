<section>
    <header>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">
            {{ __('Profile Information & Avatar') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            {{ __("Update your account's profile avatar, display name, and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Profile Avatar Picker --}}
        <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <x-input-label value="{{ __('Choose Profile Avatar') }}" class="font-bold text-slate-900 dark:text-white mb-3" />

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 mb-5">
                {{-- Current Avatar Preview --}}
                <div class="relative group">
                    <img id="avatar-preview-img" src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                        class="w-20 h-20 rounded-2xl object-cover bg-white dark:bg-slate-800 p-1 shadow-md border-2 border-blue-500/40">
                    <span class="absolute -bottom-1 -right-1 px-2 py-0.5 rounded-full bg-blue-600 text-white text-[9px] font-bold shadow">Active</span>
                </div>

                <div class="flex-1 space-y-2">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Custom Photo Upload</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Upload a custom profile photo (PNG, JPG, WEBP, up to 4MB).</p>
                    <input type="file" id="avatar_file" name="avatar_file" accept="image/*" onchange="previewCustomUpload(this)"
                        class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-500 cursor-pointer">
                    <x-input-error class="mt-1" :messages="$errors->get('avatar_file')" />
                </div>
            </div>

            {{-- DiceBear Avatar Presets Selector --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-robot text-blue-500"></i> Or Pick a DiceBear Avatar Style
                    </span>
                    <button type="button" onclick="randomizeDiceBearAvatars()" class="text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline flex items-center gap-1 cursor-pointer">
                        <i class="fa-solid fa-shuffle text-[10px]"></i> Shuffle Avatars
                    </button>
                </div>

                @php
                    $seedBase = urlencode($user->name ?? $user->email ?? 'examinee');
                    $dicebearStyles = [
                        ['name' => 'Bottts', 'url' => "https://api.dicebear.com/7.x/bottts/svg?seed={$seedBase}"],
                        ['name' => 'Adventurer', 'url' => "https://api.dicebear.com/7.x/adventurer/svg?seed={$seedBase}"],
                        ['name' => 'Avataaars', 'url' => "https://api.dicebear.com/7.x/avataaars/svg?seed={$seedBase}"],
                        ['name' => 'Fun Emoji', 'url' => "https://api.dicebear.com/7.x/fun-emoji/svg?seed={$seedBase}"],
                        ['name' => 'Notionists', 'url' => "https://api.dicebear.com/7.x/notionists/svg?seed={$seedBase}"],
                        ['name' => 'Lorelei', 'url' => "https://api.dicebear.com/7.x/lorelei/svg?seed={$seedBase}"],
                        ['name' => 'Thumbs', 'url' => "https://api.dicebear.com/7.x/thumbs/svg?seed={$seedBase}"],
                        ['name' => 'Big Smile', 'url' => "https://api.dicebear.com/7.x/big-smile/svg?seed={$seedBase}"],
                    ];
                @endphp

                <input type="hidden" id="dicebear_avatar" name="dicebear_avatar" value="">

                <div id="dicebear-presets-grid" class="grid grid-cols-4 sm:grid-cols-8 gap-2.5 pt-2">
                    @foreach($dicebearStyles as $index => $avatar)
                    <button type="button" onclick="selectDiceBearAvatar('{{ $avatar['url'] }}', this)"
                        class="dicebear-preset-btn p-1.5 rounded-xl border transition-all flex flex-col items-center gap-1 cursor-pointer group hover:scale-105 {{ $user->avatar === $avatar['url'] ? 'border-2 border-blue-600 bg-blue-50 dark:bg-blue-950/40 shadow-sm' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-blue-400' }}">
                        <img src="{{ $avatar['url'] }}" alt="{{ $avatar['name'] }}" class="w-10 h-10 rounded-lg object-contain">
                        <span class="text-[9px] font-semibold text-slate-600 dark:text-slate-400 truncate w-full text-center">{{ $avatar['name'] }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-emerald-600 font-bold flex items-center gap-1"
                ><i class="fa-solid fa-circle-check"></i> {{ __('Profile updated successfully.') }}</p>
            @endif
        </div>
    </form>
</section>

<script>
    function selectDiceBearAvatar(url, btn) {
        document.getElementById('dicebear_avatar').value = url;
        document.getElementById('avatar-preview-img').src = url;
        document.getElementById('avatar_file').value = '';

        document.querySelectorAll('.dicebear-preset-btn').forEach(b => {
            b.className = 'dicebear-preset-btn p-1.5 rounded-xl border transition-all flex flex-col items-center gap-1 cursor-pointer group hover:scale-105 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-blue-400';
        });

        if (btn) {
            btn.className = 'dicebear-preset-btn p-1.5 rounded-xl border-2 border-blue-600 bg-blue-50 dark:bg-blue-950/40 shadow-sm flex flex-col items-center gap-1 cursor-pointer group hover:scale-105';
        }
    }

    function previewCustomUpload(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview-img').src = e.target.result;
                document.getElementById('dicebear_avatar').value = '';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function randomizeDiceBearAvatars() {
        const styles = ['bottts', 'adventurer', 'avataaars', 'fun-emoji', 'notionists', 'lorelei', 'thumbs', 'big-smile'];
        const randomSeed = Math.random().toString(36).substring(7);

        const buttons = document.querySelectorAll('.dicebear-preset-btn');
        buttons.forEach((btn, index) => {
            const style = styles[index % styles.length];
            const url = `https://api.dicebear.com/7.x/${style}/svg?seed=${randomSeed}_${index}`;
            const img = btn.querySelector('img');
            if (img) img.src = url;
            btn.onclick = function() {
                selectDiceBearAvatar(url, btn);
            };
        });
    }
</script>
