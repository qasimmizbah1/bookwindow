<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Vendor;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

     protected function beforeSave(): void
    {
        // Handle vendor data before saving
        if ($this->data['role'] === 'vendor') {
            if ($this->record->vendor) {
                // Update existing vendor
                $this->record->vendor->update([
                    'vendor_name' => $this->data['vendor_name']
                ]);
            } else {
                // Create new vendor
                Vendor::create([
                    'user_id' => $this->record->id,
                    'vendor_name' => $this->data['vendor_name'],
                ]);
            }
        } elseif ($this->record->vendor) {
            // Remove vendor if role changed to admin
            $this->record->vendor->delete();
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Pre-fill vendor name if exists
        if ($this->record->vendor) {
            $data['vendor_name'] = $this->record->vendor->vendor_name; // Changed from 'name' to 'vendor_name'
        }


        return $data;
    }

}
