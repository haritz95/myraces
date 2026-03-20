@include('errors.layout', [
    'code'    => '500',
    'title'   => 'Error del servidor',
    'heading' => 'Error del <span>servidor</span>',
    'message' => 'Algo ha salido mal en nuestro lado. Estamos trabajando para solucionarlo, inténtalo de nuevo en unos momentos.',
    'icon'    => '<svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="#0a0a0a" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>',
])
