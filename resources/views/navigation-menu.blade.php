@php use Illuminate\Support\Facades\Auth; @endphp
<nav x-data="{ open: false }" class="custom-nav">
    <!-- Primary Navigation Menu -->
    <div class="custom-container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="custom-flex flex justify-between h-16">
            <div class="custom-left flex">
                <!-- Logo -->
                <div class="custom-logo shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-jet-application-mark class="block h-9 w-auto dark:fill-white"/>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="custom-links hidden space-x-4 sm:-my-px sm:ml-10 sm:flex">
                    <x-jet-nav-link class="custom-nav-link" href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link class="custom-nav-link" href="{{ route('games') }}" :active="request()->routeIs('games')">
                        {{ __('Games') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link class="custom-nav-link" href="{{ route('foods') }}" :active="request()->routeIs('foods')">
                        {{ __('Foods') }}
                    </x-jet-nav-link>
                    <x-jet-nav-link class="custom-nav-link" href="{{ route('gallery.index') }}" :active="request()->routeIs('gallery.index')">
                        {{ __('Photos') }}
                    </x-jet-nav-link>
                    @if (Auth::user()->name == "admin")
                        <x-jet-nav-link class="custom-nav-link" href="{{ route('tournament') }}" :active="request()->routeIs('tournament')">
                            <span class="text-red-400 dark:text-red-400">{{ __('Tournament') }}</span>
                        </x-jet-nav-link>
                        <x-jet-nav-link class="custom-nav-link" href="{{ route('parties') }}" :active="request()->routeIs('parties')">
                            <span class="text-red-400 dark:text-red-400">{{ __('Parties') }}</span>
                        </x-jet-nav-link>
                        <x-jet-nav-link class="custom-nav-link" href="{{ route('users') }}" :active="request()->routeIs('users')">
                            <span class="text-red-400 dark:text-red-400">{{ __('Users')}}</span>
                        </x-jet-nav-link>
                        <x-jet-nav-link class="custom-nav-link" href="{{ route('settings') }}" :active="request()->routeIs('settings')">
                            <span class="text-red-400 dark:text-red-400">{{ __('Settings')}}</span>
                        </x-jet-nav-link>
                    @endif
                </div>
            </div>

            <div class="custom-right hidden sm:flex sm:items-center sm:ml-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="custom-dropdown ml-3 relative">
                        <x-jet-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="custom-dropdown-button inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition">
                                        {{ Auth::user()->currentTeam->name }}
                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="custom-dropdown-content w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <x-jet-dropdown-link class="custom-dropdown-link"
                                                         href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-jet-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-jet-dropdown-link class="custom-dropdown-link" href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-jet-dropdown-link>
                                    @endcan

                                    <div class="border-t border-gray-100"></div>

                                    <!-- Team Switcher -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Switch Teams') }}
                                    </div>

                                    @foreach (Auth::user()->allTeams() as $team)
                                        <x-jet-switchable-team class="custom-switchable-team" :team="$team"/>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-jet-dropdown>
                    </div>
                @endif

                <div class="ml-3 relative custom-darkmode-switch">
                    <x-dark-mode-switch />
                </div>

                <!-- Settings Dropdown -->
                <div class="ml-3 relative custom-user-dropdown">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="custom-profile-button flex text-sm border-2 border-transparent rounded-full focus:outline-none transition">
                                    <img class="h-8 w-8 rounded-full object-cover"
                                         src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"/>
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="custom-user-button inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition">
                                        {{ Auth::user()->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <div class="custom-account-content">
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-jet-dropdown-link class="custom-dropdown-link" href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-jet-dropdown-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-jet-dropdown-link class="custom-dropdown-link" href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-jet-dropdown-link>
                                @endif

                                <div class="border-t border-gray-100"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-jet-dropdown-link class="custom-dropdown-link" href="{{ route('logout') }}"
                                                         onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-jet-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-jet-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden custom-hamburger">
                <button @click="open = ! open" class="custom-hamburger-button inline-flex items-center justify-center p-2 rounded-md transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden custom-responsive-menu">
        <div class="pt-2 pb-3 space-y-1 custom-responsive-links">
            <x-jet-responsive-nav-link class="custom-nav-link" href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-jet-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 custom-responsive-account">
            <div class="flex items-center px-4 custom-user-info">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 mr-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                             alt="{{ Auth::user()->name }}"/>
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1 custom-responsive-nav-links">
                <x-jet-responsive-nav-link class="custom-nav-link" href="{{ route('profile.show') }}"
                                           :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-jet-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-jet-responsive-nav-link class="custom-nav-link" href="{{ route('api-tokens.index') }}"
                                               :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-jet-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-jet-responsive-nav-link class="custom-nav-link" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                    this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-jet-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-jet-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                                               :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-jet-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-jet-responsive-nav-link href="{{ route('teams.create') }}"
                                                   :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-jet-responsive-nav-link>
                    @endcan

                    <div class="border-t border-gray-200"></div>

                    <!-- Team Switcher -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Switch Teams') }}
                    </div>

                    @foreach (Auth::user()->allTeams() as $team)
                        <x-jet-switchable-team :team="$team" component="jet-responsive-nav-link"/>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</nav>
