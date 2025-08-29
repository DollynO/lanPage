<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-jet-validation-errors class="mb-4" />

{{--        <form method="POST" action="{{ route('password.email') }}">--}}
            @csrf

            <div class="block">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
{{--                <x-jet-button>--}}
{{--                    {{ __('Email Password Reset Link') }}--}}
{{--                </x-jet-button>--}}
                <div class="container">
                <div id="runaway-container">
                    <button
                        id="runaway"
                        disabled
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                       font-semibold text-xs text-white uppercase tracking-widest
                       hover:bg-gray-700 active:bg-gray-900 focus:outline-none
                       focus:border-gray-900 focus:ring focus:ring-gray-300
                       disabled:opacity-25 transition dark:bg-gray-500">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </div>
            </div>

{{--        </form>--}}
    </x-jet-authentication-card>
</x-guest-layout>

<style>
    #runaway-container {
        position: absolute;
        left:50vw;
    }
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
    var stupidButton = {
        init: function() {
            this.attachEvents();
            this.toggleButton();
        },

        attachEvents: function() {
            $('#runaway-container').on('hover', this.flyAway);
            $('#runaway').on('click', this.showWin);

            $('#email').on('input', this.toggleButton);
        },

        flyAway: function(e) {
            if ($('#runaway').is(':disabled')) return;

            let mLeft = Math.random() * 300;
            let mTop = Math.random() * 300;

            $('#runaway-container').css({
                'margin-left': mLeft,
                'margin-top': mTop
            });
        },

        isValidEmail: function(email) {
            let re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },

        toggleButton: function() {
            let email = $('#email').val().trim();
            if (stupidButton.isValidEmail(email)) {
                $('#runaway').prop('disabled', false);
            } else {
                $('#runaway').prop('disabled', true);
            }
        },

        showWin: function(e) {
            e.preventDefault();

            $('body').css({
                'background': 'black',
                'color': 'lime',
                'font-family': 'monospace',
                'overflow': 'hidden',
                'display': 'flex',
                'align-items': 'center',
                'justify-content': 'center',
                'height': '100vh',
                'text-align': 'center',
                'font-size': '3rem',
                'line-height': '1.5'
            }).html('<pre id="matrix"></pre>');

            let text = "NICE! HERE, HAVE A COOKIE! 🍪";
            let i = 0;

            let interval = setInterval(() => {
                $('#matrix').append(text[i]);
                i++;
                if (i >= text.length) clearInterval(interval);
            }, 100);
        }

    };

    stupidButton.init();
</script>


