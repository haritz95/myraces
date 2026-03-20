@include('errors.layout', [
    'code'    => '403',
    'title'   => 'Acceso denegado',
    'heading' => 'Acceso <span>denegado</span>',
    'message' => $exception->getMessage() ?: 'No tienes permiso para acceder a esta página.',
    'icon'    => '<svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="#0a0a0a" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>',
])
