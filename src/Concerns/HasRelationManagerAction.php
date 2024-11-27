<?php

namespace Guava\FilamentModalRelationManagers\Concerns;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

use function view;

trait HasRelationManagerAction
{
    protected string|RelationManagerConfiguration $relationManager;

    protected bool $hideRelationManagerHeading = true;

    public static function getDefaultName(): ?string
    {
        return 'modal-relation-manager';
    }

    public function relationManager(RelationManagerConfiguration|string $relationManager): static
    {
        $this->relationManager = $relationManager;

        return $this;
    }

    public function getRelationManager(): RelationManagerConfiguration|string
    {
        return $this->relationManager;
    }

    public function hideRelationManagerHeading(bool $hideRelationManagerHeading = true): static
    {
        $this->hideRelationManagerHeading = $hideRelationManagerHeading;

        return $this;
    }

    public function shouldHideRelationManagerHeading(): bool
    {
        return $this->hideRelationManagerHeading;
    }

    public function configure(): static
    {
        parent::setUp();

        return $this
            ->modalContent(function (Model $record) {
                return view('guava-modal-relation-managers::components.modal-relation-manager', [
                    'relationManager' => $this->normalizeRelationManagerClass($this->getRelationManager()),
                    'ownerRecord' => $record,
                    'shouldHideRelationManagerHeading' => $this->shouldHideRelationManagerHeading(),
                    'fixIconPaddingLeft' => (bool) $this->getModalIcon() && ! in_array($this->getModalWidth(), [MaxWidth::ExtraSmall, MaxWidth::Small]),
                    'isModalSlideOver' => $this->isModalSlideOver(),
                ]);
            });
    }

    /**
     * @param  class-string<RelationManager> | RelationManagerConfiguration  $manager
     * @return class-string<RelationManager>
     */
    protected function normalizeRelationManagerClass(string|RelationManagerConfiguration $manager): string
    {
        if ($manager instanceof RelationManagerConfiguration) {
            return $manager->relationManager;
        }

        return $manager;
    }
}
