@include('errors.layout', [
    'code'    => '419',
    'title'   => 'Sesión expirada',
    'heading' => 'Sesión <span>expirada</span>',
    'message' => 'Tu sesión ha caducado por inactividad. Vuelve atrás y recarga la página para continuar.',
    'icon'    => '<svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="#0a0a0a" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
])
