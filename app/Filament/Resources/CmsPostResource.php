<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CmsPostResource\Pages;
use App\Filament\Resources\CmsPostResource\RelationManagers;
use App\Models\CmsPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Str;


class CmsPostResource extends Resource
{
    protected static ?string $model = CmsPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil';
    protected static ?string $navigationGroup = 'Blog';
    protected static ?int $navigationSort = 2;
     protected static ?string $modelLabel = 'Blog Post';
    protected static ?string $pluralModelLabel = 'Blog Posts';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

           
            TextInput::make('title')
                ->required()
                ->live(debounce: 500)
                ->afterStateUpdated(fn ($state, callable $set) => 
                $set('slug', Str::slug($state))
                ),
                TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true),
                Select::make('cms_category_id')
                ->relationship('category', 'name')
                ->required()
                ->columnSpan(2),
            RichEditor::make('content')
            ->columnSpan(2),
            Forms\Components\FileUpload::make(name:'image')
            ->label('Feature Image')
            ->required()
            ->columnSpan(2),
            Forms\Components\TextInput::make('meta_title')
                    ->maxLength(255),
                 Forms\Components\TextInput::make('meta_keywords')
                    ->maxLength(255),

                Forms\Components\Textarea::make('meta_description')
                    ->maxLength(255)
                    ->columnSpanfull(),

            Toggle::make('is_active')->label('Active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
             Tables\Columns\ImageColumn::make(name: 'image'),
            Tables\Columns\TextColumn::make('title'),
            Tables\Columns\TextColumn::make('category.name')->label('Category'),
            Tables\Columns\TextColumn::make('slug'),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
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
            'index' => Pages\ListCmsPosts::route('/'),
            'create' => Pages\CreateCmsPost::route('/create'),
            'edit' => Pages\EditCmsPost::route('/{record}/edit'),
        ];
    }
}
