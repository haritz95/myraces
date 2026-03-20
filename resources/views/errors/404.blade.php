@include('errors.layout', [
    'code'    => '404',
    'title'   => 'Página no encontrada',
    'heading' => 'Página <span>no encontrada</span>',
    'message' => 'Parece que esta página no existe o ha sido movida. Comprueba la URL o vuelve al inicio.',
    'icon'    => '<svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="#0a0a0a" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>',
])
