<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Spatie\Permission\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    public static function getNavigationIcon(): ?string
    {
        return config('filament-spatie-roles-permissions.icons.role_navigation');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-spatie-roles-permissions.should_register_on_navigation.roles', true);
    }

    public static function getModel(): string
    {
        return config('permission.models.role', Role::class);
    }

    public static function getLabel(): string
    {
        return __('filament-spatie-roles-permissions::filament-spatie.section.role');
    }

    public static function getNavigationGroup(): ?string
    {
        return __(config('filament-spatie-roles-permissions.navigation_section_group', 'filament-spatie-roles-permissions::filament-spatie.section.roles_and_permissions'));
    }

    public static function getNavigationSort(): ?int
    {
        return config('filament-spatie-roles-permissions.sort.role_navigation');
    }

    public static function getPluralLabel(): string
    {
        return __('filament-spatie-roles-permissions::filament-spatie.section.roles');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.name'))
                                    ->required()
                                    ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                        // If using teams and Tenancy, ensure uniqueness against current tenant
                                        if (config('permission.teams', false) && Filament::hasTenancy()) {
                                            // Check uniqueness against current user/team
                                            $rule->where(config('permission.column_names.team_foreign_key', 'team_id'), Filament::getTenant()->id);
                                        }
                                        return $rule;
                                    })
                                    ->columnSpanFull(),

                                Select::make('guard_name')
                                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.guard_name'))
                                    ->options(config('filament-spatie-roles-permissions.guard_names'))
                                    ->default(config('filament-spatie-roles-permissions.default_guard_name'))
                                    ->visible(fn() => config('filament-spatie-roles-permissions.should_show_guard', true))
                                    ->required(),

                                Select::make('permissions')
                                    ->columnSpanFull()
                                    ->multiple()
                                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.permissions'))
                                    ->relationship(
                                        name: 'permissions',
                                        modifyQueryUsing: fn(Builder $query) => $query->orderBy('name'),
                                    )
                                    ->visible(config('filament-spatie-roles-permissions.should_show_permissions_for_roles'))
                                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->name} ({$record->guard_name})")
                                    ->searchable(['name', 'guard_name']) // searchable on both name and guard_name
                                    ->preload(config('filament-spatie-roles-permissions.preload_permissions')),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.name'))
                    ->searchable(),
                TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.permissions_count'))
                    ->toggleable(isToggledHiddenByDefault: config('filament-spatie-roles-permissions.toggleable_guard_names.roles.isToggledHiddenByDefault', true)),
                TextColumn::make('guard_name')
                    ->toggleable(isToggledHiddenByDefault: config('filament-spatie-roles-permissions.toggleable_guard_names.roles.isToggledHiddenByDefault', true))
                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.guard_name'))
                    ->searchable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions(
                config('filament-spatie-roles-permissions.should_remove_empty_state_actions.roles') ? [] :
                [
                    Tables\Actions\CreateAction::make()
                ]
            );
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoles::route('/'),
        ];
    }
}
