<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Symfony\Contracts\Service\Attribute\Required;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use App\Imports\OrdersImport;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Filters\TrashedFilter;


use function Livewire\Volt\dehydrate;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = "Shop";

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'pending')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                    Wizard::make([
                        Wizard\Step::make('Order Detials')
                            ->schema([
                                Forms\Components\TextInput::make( name: 'order_number')
                                ->label('Order Id')
                                ->disabled()
                                ->required(),
                                Select::make(name:'status')
                                ->options([
                                    "pending"=>"Pending",
                                     "processing"=>"Processing",
                                      "completed"=>"Completed",
                                       "declined"=>"Declined",
                                ])->required(),

                               
                                TextInput::make('shipping_amount')
                                ->disabled(),

                                 TextInput::make('total_amount')
                                ->disabled(),





                            ])->columns(2),



                        Wizard\Step::make('Order Item')
                            ->schema([
                              
                                Repeater::make('items')
    ->relationship('items') // Make sure this matches your relationship name in the Order model
    ->schema([
        Select::make('product_id')
            ->label('Products')
            ->searchable()
            ->options(Product::query()->pluck('name', 'id'))
            ->required()
            ->reactive() // Add this to update price when product changes
            ->afterStateUpdated(function ($state, callable $set) {
                // Update price when product is selected
                $product = Product::find($state);
                if ($product) {
                    $set('price', $product->price);
                }
            }),
            
        TextInput::make('quantity')
            ->label('Stock Quantity')
            ->numeric()
            ->default(1)
            ->required()
            ->minValue(1),
            
        TextInput::make('price')
            ->label('Unit Price')
            ->numeric()
            ->disabled()
            ->required(),
            
        Select::make('payment_method')
            ->options([
                'card' => 'Credit Card',
                'cod' => 'Cash on Delivery',
            ])
            ->required(),
    ])
    ->defaultItems(1)
    ->columns(2),


                                

                            ]),
                    ])->columnSpanFull(),
                

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(name: 'id')->sortable(),
                Tables\Columns\TextColumn::make(name: 'order_number')->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                ->label('Customer Name')
                ->getStateUsing(function ($record) {
                return $record->customername->first_name . ' ' . $record->customername->last_name;
                })
                ->sortable(query: function (Builder $query, string $direction) {
                $query->join('customernames', 'customernames.id', '=', 'your_table.customer_id')
                ->orderBy('customernames.first_name', $direction)
                ->orderBy('customernames.last_name', $direction);
                })
                ->searchable(query: function (Builder $query, string $search) {
                $query->whereHas('customername', function (Builder $query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%");
                });
                })
                ->toggleable(),

                Tables\Columns\TextColumn::make(name: 'status')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make(name: 'total_amount')
                ->label('Total Amount')
                ->sortable()
                ->searchable()
                ->summarize(
                    [
                        Tables\Columns\Summarizers\Sum::make()->money(),
                    ]
                ),
                Tables\Columns\TextColumn::make(name: 'created_at')
                ->label(label : "Order Date")
                ->date(),
            ])
            ->defaultSort('id', 'desc')
            ->headerActions([
                CreateAction::make(),
                Action::make('import')
                    ->label('Import Orders')
                    ->action(function (array $data) {
                        $file = storage_path('app/public/' . $data['file']);
                        if (!file_exists($file)) {
                            throw new \Exception("File not found: " . $file);
                        }

                        Excel::import(new OrdersImport(), $file);
                    })
                    ->form([
                        FileUpload::make('file')
                            ->label('Excel File')
                            ->required()
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-excel',
                            ]),
                    ]),
            ])
            ->filters([
                 TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'import' => Pages\ImportOrders::route('/import'),
        ];
    }
}
