<?php
// app/Filament/Resources/HomePageResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\HomePageResource\Pages;
use App\Filament\Resources\HomePageResource\RelationManagers;
use App\Models\HomePage;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;


class HomePageResource extends Resource
{
    protected static ?string $model = HomePage::class;
    protected static ?string $navigationGroup = 'Pages';
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $modelLabel = 'Home Page';
    protected static ?string $navigationLabel = 'Home Page';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Slider Section
                Forms\Components\Section::make('Banner Section')
                    ->schema([

                        
                        Forms\Components\FileUpload::make('banner_logo')
                            ->label('Logo Image')
                            ->image()
                            ->directory('home-page/banner')
                            ->reorderable(),

                        Forms\Components\FileUpload::make('banner_images')
                            ->label('Banner Image')
                            ->image()
                            ->directory('home-page/banner')
                            ->reorderable(),
                         Forms\Components\RichEditor::make('banner_description')
                            ->label('Banner Description')
                             ->columnSpanFull(),

                        
                        
                        Forms\Components\TextInput::make('banner_button_title')
                            ->label('Button text'),
                        Forms\Components\TextInput::make('banner_button_url')
                            ->label('Button URL'),

                    ]) ->columns(2),
                   
                // Categories Sections
                Forms\Components\Section::make('Category Sections')
                    ->schema([

                        Forms\Components\TextInput::make('cat_sec_title')
                                    ->label('Title')
                                    ->required()
                                    ->columnSpanFull(),
                        Forms\Components\RichEditor::make('cat_sec_description')
                            ->label('Section Description')
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('category_sections')
                            ->label('Category')
                            ->schema([
                            Forms\Components\FileUpload::make('cat_icon')
                            ->label('Category Icon')
                            ->image()
                            ->directory('home-page/catgegory')
                            ->reorderable(),

                                Forms\Components\TextInput::make('cat_title')
                                    ->label('Title')
                                    ->required()
                                    ,
                                Forms\Components\FileUpload::make('cat_image')
                                    ->label('Image')
                                    ->image()
                                    ->directory('home-page/catgegory'),
                                Forms\Components\RichEditor::make('cat_content')
                                    ->label('Content'),
                                Forms\Components\TextInput::make('cat_button_title')
                                ->label('Button text'),
                                Forms\Components\TextInput::make('cat_button_url')
                                ->label('Button URL'),
                            ])
                            ->columns(2)
                            ,
                    ]),

                    // Category Tabs
                Forms\Components\Section::make('Category Tab')
                    ->schema([

                        Forms\Components\TextInput::make('cat_tab_subtitle')
                                    ->label('Sub Title')
                                    ->required(),

                        Forms\Components\TextInput::make('cat_tab_title')
                                    ->label('Title')
                                    ->required(),

                        Forms\Components\RichEditor::make('cat_tab_description')
                            ->label('Section Description')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('cat_tabs')
                            ->label('Select Category')
                            ->options(Category::pluck('name', 'id'))
                            ->multiple()
                            ->searchable(['name'])
                            ->afterStateUpdated(fn ($state) => is_array($state) ? implode(',', $state) : $state)
                            ->columnSpanFull(),
                        
                        
                    ])->columns(2),

                        // Testimonial Sections
                        Forms\Components\Section::make('Testimonial Sections')
                        ->schema([
                        Forms\Components\Repeater::make('testimonial_sections')
                        ->label('Sections')

                        ->schema([
                        Forms\Components\RichEditor::make('testimonial_content')
                        ->label('Content'),

                        Forms\Components\FileUpload::make('testimonial_image')
                        ->label('Image')
                        ->image()
                        ->directory('home-page/testimonial'),
                        
                        Forms\Components\TextInput::make('testimonial_button_title')
                        ->label('Button text'),
                        Forms\Components\TextInput::make('testimonial_button_url')
                        ->label('Button URL'),
                        ])->columns(2)
                        ,
                        ]),
                        
                        // Custom Sections
                        Forms\Components\Section::make('Feature Sections')
                        ->schema([
                         Forms\Components\TextInput::make('feature_title')
                                    ->label('Title')
                                    ->required()
                                    ->columnSpanFull(),
                        Forms\Components\RichEditor::make('feature_description')
                            ->label('Description')
                            ->columnSpanFull(),

                        Forms\Components\Repeater::make('custom_sections')
                        ->label('Feature Sections')
                        ->schema([
                        Forms\Components\TextInput::make('title')
                        ->label('Section Title')
                        ->required()
                        ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image')
                        ->label('Image')
                        ->image()
                        ->directory('home-page/sections'),
                        Forms\Components\RichEditor::make('content')
                        ->label('Content'),
                        ])
                        ->columns(2),
                        ]),
                        ]);

                     // // Featured Products Section
                     //        Forms\Components\Section::make('Featured Products')
                     //        ->schema([
                     //        Forms\Components\TextInput::make('featured_products_title')
                     //        ->label('Section Title'),

                     //        Select::make('featured_products')
                     //        ->label('Select Products')
                     //        ->options(Product::pluck('name', 'id'))
                     //        ->multiple()
                     //        ->searchable(['name', 'sku'])
                     //        ->afterStateUpdated(fn ($state) => is_array($state) ? implode(',', $state) : $state)
                     //        ->columnSpanFull(),
                     //        ]),



                // Best Sellers Section
                // Forms\Components\Section::make('Best Sellers')
                //     ->schema([
                //         Forms\Components\TextInput::make('best_sellers_title')
                //             ->label('Section Title'),
                //         Forms\Components\Select::make('best_sellers')
                //             ->label('Select Products')
                //             ->multiple()
                //             ->relationship('bestSellers', 'name')
                //             ->preload()
                //             ->columnSpanFull(),
                //     ]),
                
                // Latest Products Section
                // Forms\Components\Section::make('Latest Products')
                //     ->schema([
                //         Forms\Components\TextInput::make('latest_products_title')
                //             ->label('Section Title'),
                //         // Latest products will be fetched dynamically in the frontend
                //     ]),
                
                // Categories Section
                // Forms\Components\Section::make('Categories')
                //     ->schema([
                //         Forms\Components\Repeater::make('categories')
                //             ->label('Categories')
                //             ->schema([
                //                 Forms\Components\Select::make('category_id')
                //                     ->label('Category')
                //                     ->relationship('categories', 'name') // Assuming you have a Category model
                //                     ->required(),
                //                 Forms\Components\TextInput::make('button_text')
                //                     ->label('Button Text')
                //                     ->default('View More'),
                //             ])
                //             ->columns(2)
                //             ->columnSpanFull(),
                //     ]),
                
                
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 Tables\Columns\TextColumn::make('page_title')
                    ->label('Page')
                    ->weight('bold')
                    ,
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListHomePages::route('/'),
            'create' => Pages\CreateHomePage::route('/create'),
            'edit' => Pages\EditHomePage::route('/{record}/edit'),
        ];
    }

    public static function getNavigationUrl(): string
{
    $recordId = \App\Models\HomePage::query()->first()?->id;

    return $recordId
        ? static::getUrl('edit', ['record' => $recordId])
        : static::getUrl('index'); // fallback
}

}