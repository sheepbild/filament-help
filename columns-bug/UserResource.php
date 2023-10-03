<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use App\Models\Groupe;
use App\Models\Professionnel;
use App\Models\Tag;
use App\Models\TherapieCategorie;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Utilisateurs';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Rôles et Permissions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Général')
                    ->schema([
                        TextInput::make('prenom')->label('Prénom')->required(),
                        TextInput::make('nom')->required(),
                        TextInput::make('tel')->label('Téléphone')->required(),
                        TextInput::make('email')->required(),
                        DatePicker::make('birth_date')->label('Date de naissance')->required(),
                        Radio::make('sexe')
                            ->options([
                                'Homme' => 'Homme',
                                'Femme' => 'Femme',
                                '' => 'Non spécifié'
                            ]),
                    ]),
                Fieldset::make('Adresse')
                    ->schema([
                        TextInput::make('adresse_rue')->label('Rue'),
                        TextInput::make('adresse_cp')->label('Code postal'),
                        TextInput::make('adresse_ville')->label('Ville'),
                        TextInput::make('adresse_pays')->label('Pays'),
                    ]),
                Fieldset::make('Autre')
                    ->schema([
                        Select::make('groupe_id')
                            ->label('Groupe')
                            ->options(Groupe::all()->sortBy('title')->pluck('title', 'id')),
                        Toggle::make('is_newsletter')
                            ->onIcon('heroicon-m-inbox-arrow-down')
                            ->offIcon('heroicon-m-inbox')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nom')->sortable(),
                ViewColumn::make('contact')->view('tables.columns.user_contact')->label('Contact'),
                TextColumn::make('statut')->label('Statut')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
