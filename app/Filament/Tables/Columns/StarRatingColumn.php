<?php

namespace App\Filament\Tables\Columns;

use Filament\Support\Components\Contracts\HasEmbeddedView;
use Filament\Tables\Columns\Column;
use Livewire\Livewire;

class StarRatingColumn extends Column implements HasEmbeddedView
{
    public function toEmbeddedHtml(): string
    {

        $component = Livewire::mount('rating.star-rating', [
            'record' => $this->getRecord(),
        ]);

        ob_start(); ?>

        <div>
            <?= $component ?>
        </div>

        <?php return ob_get_clean();
    }
}
