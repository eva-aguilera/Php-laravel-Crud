<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;

class UfDisplay extends Component
{
    
   public $ufValue;
    public $ufDate;

    /**
     * Crea una nueva instancia del componente.
     */
    public function __construct()
    {
        // La API de Mindicador es simple y estable
        $apiUrl = 'https://mindicador.cl/api/uf';
        
        try {
            $response = Http::get($apiUrl);
            
            // Si la solicitud es exitosa y tiene datos
            if ($response->successful() && isset($response->json()['serie'][0])) {
                $data = $response->json()['serie'][0];
                
                // Formatear el valor (e.g., 34.567,89)
                $this->ufValue = '$ ' . number_format($data['valor'], 2, ',', '.');
                
                // Formatear la fecha (del formato ISO a d/m/Y)
                $this->ufDate = date('d-m-Y', strtotime($data['fecha']));
                
            } else {
                $this->ufValue = 'N/D';
                $this->ufDate = date('d-m-Y');
            }
        } catch (\Exception $e) {
            // Manejo de errores de conexión
            $this->ufValue = 'Error de conexión';
            $this->ufDate = date('d-m-Y');
        }
    }

    /**
     * Obtener la vista/contenido que representa el componente.
     */
    public function render(): View|Closure|string
    {
        return view('components.uf-display');
    }
}
