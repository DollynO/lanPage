<tr
    {{ $attributes->merge([
        'class' => 'fi-ta-row fi-ta-summary-row cursor-pointer' . ($selected??0 ? ' !bg-[#f5f5f5] dark:!bg-[#212121e6]' : '')
    ]) }}
>
    {{ $slot }}
</tr>
