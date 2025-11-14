<div class="fi-ta">
    <div class="fi-ta-ctn fi-ta-ctn-with-footer fi-ta-ctn-with-header">
        <div class="fi-ta-main">
            <div class="fi-ta-header-ctn">
                <div class="fi-ta-header fi-ta-header-adaptive-actions-position">
                    {{ $header ?? '' }}
                </div>
                @isset($toolbar)
                    <div class="fi-ta-header-toolbar">
                        {{ $toolbar ?? '' }}
                    </div>
                @endisset
            </div>
            <div class="fi-ta-content-ctn">
                <table class="fi-ta-table">
                {{$slot}}
                </table>
            </div>
            <nav class="fi-pagination">
                {{ $footer ?? '' }}
            </nav>
        </div>
    </div>
</div>



