<div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
     x-init="
        if(darkMode) {
          document.documentElement.classList.add('dark');
        } else {
          document.documentElement.classList.remove('dark');
        }
     "
     @click="
        darkMode = !darkMode;
        if(darkMode) {
          document.documentElement.classList.add('dark');
          localStorage.setItem('darkMode', 'true');
        } else {
          document.documentElement.classList.remove('dark');
          localStorage.setItem('darkMode', 'false');
        }
     "
     class="cursor-pointer flex items-center space-x-2 select-none"
>
    <!-- Switch -->
    <div :class="darkMode ? 'bg-indigo-600' : 'bg-gray-300'"
         class="w-12 h-6 rounded-full relative transition-colors duration-300">
        <div :class="darkMode ? 'translate-x-6' : 'translate-x-0'"
             class="bg-white w-6 h-6 rounded-full shadow transform transition-transform duration-300 absolute top-0 left-0"></div>
    </div>
    <!-- Label -->
    <span x-text="darkMode ? 'Dark Mode' : 'Light Mode'" class="text-gray-700 dark:text-gray-300"></span>
</div>
