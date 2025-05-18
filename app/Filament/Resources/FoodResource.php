<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodResource\Pages;
use App\Filament\Widgets\FoodPriceHistoryChart;
use App\Models\Food;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;
use Livewire\Livewire;

class FoodResource extends Resource
{
    protected static ?string $model = Food::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'খাদ্য সামগ্রী';
    protected static ?string $modelLabel = 'খাদ্য';
    protected static ?string $pluralModelLabel = 'খাদ্য সামগ্রী';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('নাম')
                    ->required()
                    ->live()
                    ->debounce('500ms')
                    ->helperText('খাদ্য সামগ্রীর নাম লিখুন (কমপক্ষে ৩ অক্ষর)')
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if (strlen($state) >= 3) {
                            $set('is_loading', true);
                            $suggestion = self::getPriceSuggestion($state);
                            Log::info('Price suggestion response:', ['suggestion' => $suggestion]);
                            
                            if ($suggestion && isset($suggestion['price']) && isset($suggestion['unit'])) {
                                $set('price', $suggestion['price']);
                                $set('unit', $suggestion['unit']);
                                $set('is_loading', false);
                            } else {
                                $set('is_loading', false);
                            }
                        } else {
                            $set('price', null);
                            $set('unit', null);
                            $set('is_loading', false);
                        }
                    }),
                Forms\Components\TextInput::make('price')
                    ->label('মূল্য')
                    ->required()
                    ->numeric()
                    ->prefix('৳')
                    ->disabled(fn (Forms\Get $get) => $get('is_loading') || !$get('name'))
                    ->helperText('খাদ্য সামগ্রীর মূল্য টাকায়')
                    ->placeholder('মূল্য লিখুন...'),
                Forms\Components\TextInput::make('unit')
                    ->label('একক')
                    ->required()
                    ->disabled(fn (Forms\Get $get) => $get('is_loading') || !$get('name'))
                    ->helperText('খাদ্য সামগ্রীর একক (যেমন: কেজি, লিটার, পিস)')
                    ->placeholder('একক লিখুন...'),
                Forms\Components\Hidden::make('is_loading')
                    ->default(false),
                Forms\Components\Placeholder::make('loading_indicator')
                    ->content('মূল্য এবং একক লোড হচ্ছে...')
                    ->visible(fn (Forms\Get $get) => $get('is_loading'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('নাম')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('মূল্য')
                    ->money('BDT')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit')
                    ->label('একক'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('তৈরি করা হয়েছে')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListFoods::route('/'),
            'create' => Pages\CreateFood::route('/create'),
            'edit' => Pages\EditFood::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            FoodPriceHistoryChart::class,
        ];
    }

    public static function getPriceSuggestion(string $name): ?array
    {
        try {
            // First check if we have existing data for this food item
            $existingFood = Food::where('name', 'like', "%{$name}%")
                ->orderBy('created_at', 'desc')
                ->first();

            if ($existingFood) {
                return [
                    'price' => $existingFood->price,
                    'unit' => $existingFood->unit
                ];
            }

            // If no existing data, get suggestion from Gemini
            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')
                ->generateContent("You are a helpful assistant that suggests current market prices for food items in Bangladesh. What is the current market price and unit for {$name} in Bangladesh? Consider recent market trends and current prices. Provide a single average price (not a range) and the unit. Respond in JSON format like: {\"price\": 100, \"unit\": \"kg\"}");

            $content = $result->text();
            Log::info('Gemini API response:', ['content' => $content]);
            
            if ($content) {
                // Extract JSON from the response
                if (preg_match('/```json\s*(\{.*?\})\s*```/s', $content, $matches)) {
                    $jsonStr = $matches[1];
                } else {
                    $jsonStr = $content;
                }
                
                $data = json_decode($jsonStr, true);
                if (is_array($data) && isset($data['price']) && isset($data['unit'])) {
                    // Handle price ranges (e.g., "140-180")
                    $price = $data['price'];
                    if (is_string($price) && str_contains($price, '-')) {
                        $prices = array_map('floatval', explode('-', $price));
                        $price = array_sum($prices) / count($prices); // Calculate average
                    }
                    
                    return [
                        'price' => (float) $price,
                        'unit' => $data['unit']
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Price suggestion error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return null;
    }
}
