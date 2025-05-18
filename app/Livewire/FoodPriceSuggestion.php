<?php

namespace App\Livewire;

use App\Models\AiSetting;
use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

class FoodPriceSuggestion extends Component
{
    public $name;
    public $suggestedPrice;
    public $suggestedUnit;
    public $isLoading = false;
    public $error;

    public function getPriceSuggestion($data)
    {
        $this->name = $data['name'];
        $this->isLoading = true;
        $this->error = null;

        try {
            $settings = AiSetting::pluck('value', 'key')->toArray();
            
            $response = OpenAI::chat()->create([
                'model' => $settings['model'] ?? 'gpt-4-turbo-preview',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $settings['prompt_template'] ?? 'Analyze the following food item and suggest its current market price and unit in Bangladesh: {name}'
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->name
                    ]
                ],
                'temperature' => $settings['temperature'] ?? 0.7,
            ]);

            $content = $response->choices[0]->message->content;
            
            // Parse the response to extract price and unit
            if (preg_match('/price:\s*৳?\s*(\d+(?:\.\d{2})?)/i', $content, $priceMatches) &&
                preg_match('/unit:\s*([a-zA-Z]+)/i', $content, $unitMatches)) {
                $this->suggestedPrice = $priceMatches[1];
                $this->suggestedUnit = $unitMatches[1];
                
                $this->dispatch('priceSuggestionReceived', [
                    'price' => $this->suggestedPrice,
                    'unit' => $this->suggestedUnit
                ]);
            } else {
                $this->error = 'Could not parse AI response';
            }
        } catch (\Exception $e) {
            $this->error = 'Failed to get price suggestion: ' . $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.food-price-suggestion');
    }
}
