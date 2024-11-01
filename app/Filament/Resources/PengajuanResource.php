<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanResource\Pages;
use App\Filament\Resources\PengajuanResource\RelationManagers;
use App\Models\Pengajuan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengajuanResource extends Resource
{
    protected static ?string $model = Pengajuan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Pengajuan';
    protected static ?string $pluralModelLabel = 'pengajuan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_pengaju')
                    ->label('Nama Pengaju')
                    ->required(),
                Forms\Components\TextInput::make('nama_kegiatan')
                    ->label('Nama Kegiatan')
                    ->required(),
                Forms\Components\FileUpload::make('bukti_kegiatan')
                    ->label('Bukti Kegiatan')
                    ->directory('bukti_kegiatan')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(2048)
                    ->required(),
                Forms\Components\TextInput::make('nominal_rab')
                    ->label('Nominal RAB')
                    ->numeric()
                    ->required(),
                Forms\Components\Repeater::make('rincian_kebutuhan')
                    ->label('Rincian Kebutuhan')
                    ->required()
                    ->schema([
                        Forms\Components\TextInput::make('item')
                            ->label('Nama Item')
                            ->required(),
                        Forms\Components\TextInput::make('qty')
                            ->label('Qty')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('satuan')
                            ->label('Satuan')
                            ->required()
                            ->options([
                                'PCS' => 'PCS',
                                'UNIT' => 'UNIT',
                                'SET' => 'SET',
                                'LITER' => 'LITER',
                                'KILogram' => 'KILOGRAM',
                                'TON' => 'TON',
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pengaju'),
                Tables\Columns\TextColumn::make('nama_kegiatan'),
                Tables\Columns\TextColumn::make('bukti_kegiatan')
                ->label('Bukti Kegiatan')
                ->formatStateUsing(fn ($state) => $state ? "<a href='" . asset('storage/' . $state) . "' target='_blank'>Lihat File</a>" : 'Tidak Ada File')
                ->html(),
                Tables\Columns\TextColumn::make('nominal_rab'),
                // Tables\Columns\TextColumn::make('rincian_kebutuhan')
                Tables\Columns\TextColumn::make('rincian_kebutuhan')
                ->label('Rincian Kebutuhan')
                ->formatStateUsing(function ($state) {
                    if (is_array($state)) {
                        return collect($state)
                            ->map(function ($item) {
                                return "Qty: {$item['qty']}, Item: {$item['item']}, Satuan: {$item['satuan']}";
                            })
                            ->join('; ');
                    }
                    return 'Tidak Ada Data';
                })
                ->wrap(),
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
            'index' => Pages\ListPengajuans::route('/'),
            'create' => Pages\CreatePengajuan::route('/create'),
            'edit' => Pages\EditPengajuan::route('/{record}/edit'),
        ];
    }
}
