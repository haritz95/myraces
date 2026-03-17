<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class RaceCoachController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $races = $user->races()->where('status', 'completed')->orderByDesc('date')->limit(10)->get();
        $personalRecords = $user->personalRecords()->orderByDesc('date')->get();
        $totalExpenses = $user->expenses()->sum('amount');

        return view('coach.index', compact('races', 'personalRecords', 'totalExpenses'));
    }

    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $message = $request->string('message');

        $context = $this->buildAthleteContext($user);
        $response = $this->generateCoachResponse($message, $context);

        return response()->json(['response' => $response]);
    }

    private function buildAthleteContext(User $user): string
    {
        $races = $user->races()->where('status', 'completed')->orderByDesc('date')->limit(10)->get();
        $prs = $user->personalRecords()->orderByDesc('date')->get();
        $totalExpenses = $user->expenses()->sum('amount');
        $totalKm = $races->where('distance_unit', 'km')->sum('distance');
        $totalRaces = $races->count();

        $prSummary = $prs->groupBy('distance_label')->map(function ($group) {
            $best = $group->sortBy('time_seconds')->first();

            return "{$best->distance_label}: {$best->formatted_time}";
        })->implode(', ');

        $recentRaces = $races->take(5)->map(function ($race) {
            $time = $race->formatted_time ?? 'sin tiempo';
            $pace = $race->pace ?? '-';

            return "{$race->name} ({$race->formatted_distance} km, {$time}, ritmo: {$pace})";
        })->implode('; ');

        return <<<TEXT
        Atleta: {$user->name}
        Total carreras completadas: {$totalRaces}
        Kilómetros totales: {$totalKm} km
        Gasto total en deporte: {$totalExpenses} EUR
        Récords personales: {$prSummary}
        Últimas carreras: {$recentRaces}
        TEXT;
    }

    private function generateCoachResponse(string $message, string $context): string
    {
        $apiKey = config('services.anthropic.key');

        if (! $apiKey) {
            return $this->getFallbackResponse($message, $context);
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-haiku-4-5-20251001',
                'max_tokens' => 500,
                'system' => "Eres RaceCoach, un entrenador personal de atletismo experto y motivador. Analizas datos de rendimiento y das consejos prácticos, motivadores y basados en datos. Responde siempre en español, de forma concisa (máximo 3 párrafos). Aquí están los datos del atleta:\n\n{$context}",
                'messages' => [
                    ['role' => 'user', 'content' => $message],
                ],
            ]);

            if ($response->successful()) {
                return $response->json('content.0.text', 'No pude generar una respuesta.');
            }
        } catch (\Exception) {
            // Fall through to fallback
        }

        return $this->getFallbackResponse($message, $context);
    }

    private function getFallbackResponse(string $message, string $context): string
    {
        $lowerMessage = strtolower($message);

        if (str_contains($lowerMessage, 'pr') || str_contains($lowerMessage, 'récord') || str_contains($lowerMessage, 'record')) {
            return 'Basándome en tus datos, puedo ver tu historial de récords personales. Para mejorar tus marcas, te recomiendo: 1) Incluir al menos un entrenamiento de series semanales, 2) Aumentar el volumen gradualmente (máximo 10% por semana), y 3) Asegurarte de descansar adecuadamente entre sesiones intensas. ¡Cada entrenamiento te acerca a tu siguiente récord!';
        }

        if (str_contains($lowerMessage, 'tiempo') || str_contains($lowerMessage, 'predicci') || str_contains($lowerMessage, 'predeci')) {
            return 'Para predecir tiempos en carreras de otras distancias, uso la fórmula de Riegel: T2 = T1 × (D2/D1)^1.06. Si tienes un récord de 5K de 25 minutos, tu predicción para 10K sería aproximadamente 52 minutos. Recuerda que estas son estimaciones; el entrenamiento específico para cada distancia puede mejorar significativamente esos tiempos.';
        }

        if (str_contains($lowerMessage, 'gasto') || str_contains($lowerMessage, 'dinero') || str_contains($lowerMessage, 'presupuesto')) {
            return 'Analizar tus gastos deportivos es muy inteligente. Las principales categorías de gasto en atletismo son las inscripciones, el material (especialmente zapatillas), y los desplazamientos. Te recomiendo reservar con antelación para obtener precios early bird en carreras populares, y hacer un seguimiento del km por euro en tus zapatillas para optimizar cuándo cambiarlas.';
        }

        return '¡Hola! Soy tu RaceCoach personal. Puedo ayudarte a analizar tu rendimiento, predecir tiempos de carrera, optimizar tu plan de entrenamiento y gestionar tus gastos deportivos. Pregúntame sobre tus récords, próximas carreras, o cualquier aspecto de tu preparación atlética. ¿En qué te puedo ayudar hoy?';
    }
}
