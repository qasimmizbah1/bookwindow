<?php

// namespace App\Filament\Resources\ProductResource\Pages;

// use App\Filament\Resources\ProductResource;
// use Filament\Resources\Pages\Page;

// class ImportProducts extends Page
// {
//     protected static string $resource = ProductResource::class;

//     protected static string $view = 'filament.resources.product-resource.pages.import-products';
// }


namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Imports\ProductsImport;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class ImportProducts extends Page
{
    protected static string $resource = ProductResource::class;
    protected static string $view = 'filament.resources.product-resource.pages.import-products';

    public $file;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import')
                ->label('Import Data')
                ->action('import'),
        ];
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ProductsImport, $this->file);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Products imported successfully!',
        ]);
    }
}