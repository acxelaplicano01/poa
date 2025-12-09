# Configuración del Módulo de IA para Actividades

## 📋 Descripción

El módulo de IA permite generar automáticamente los campos de una actividad basándose únicamente en el nombre de la actividad. Soporta múltiples proveedores de IA:

- ✅ **OpenAI** (GPT-4o-mini)
- ✅ **Google Gemini** (Gemini Pro)

Genera automáticamente:
- ✅ Descripción detallada de la actividad
- ✅ Resultado esperado
- ✅ Población objetivo
- ✅ Medio de verificación

## 🔧 Configuración

### Opción 1: Usar OpenAI

#### Paso 1: Obtener API Key de OpenAI
1. Visita [https://platform.openai.com/api-keys](https://platform.openai.com/api-keys)
2. Inicia sesión o crea una cuenta
3. Haz clic en "Create new secret key"
4. Copia la clave (guárdala en un lugar seguro)

#### Paso 2: Configurar en .env
```env
IA_PROVIDER=openai
OPENAI_API_KEY=sk-proj-abc123xyz456...
```

### Opción 2: Usar Google Gemini (Recomendado)

#### Paso 1: Obtener API Key de Gemini
1. Visita [https://makersuite.google.com/app/apikey](https://makersuite.google.com/app/apikey)
2. Inicia sesión con tu cuenta de Google
3. Haz clic en "Create API Key"
4. Copia la clave

#### Paso 2: Configurar en .env
```env
IA_PROVIDER=gemini
GEMINI_API_KEY=AIzaSyAbc123xyz456...
```

### Configuración Adicional (Opcional)

```env
# Tiempo mínimo entre solicitudes (en segundos)
IA_THROTTLE_SECONDS=30

# Modelo específico de OpenAI (opcional)
OPENAI_MODEL=gpt-4o-mini

# Modelo específico de Gemini (opcional)
GEMINI_MODEL=gemini-pro
```

### Paso 3: Limpiar caché

```bash
php artisan config:clear
php artisan cache:clear
```

## 🔄 Cambiar de Proveedor

Para cambiar entre OpenAI y Gemini, simplemente modifica el `.env`:

```env
# Para usar OpenAI
IA_PROVIDER=openai

# Para usar Gemini
IA_PROVIDER=gemini
```

Luego ejecuta:
```bash
php artisan config:clear
```

## 💡 Cómo Usar el Módulo de IA

1. **Crear Nueva Actividad**: Haz clic en "Nueva Actividad"
2. **Activar IA**: En el modal, haz clic en el botón "Generar con IA" (esquina superior derecha)
3. **Ingresar Nombre**: Escribe el nombre de la actividad de forma clara y específica
   - ✅ Bueno: "Capacitación docente en metodologías activas de enseñanza"
   - ❌ Malo: "Capacitación"
4. **Generar**: Haz clic en "Generar Actividad"
5. **Revisar y Ajustar**: La IA completará los campos automáticamente. Revísalos y ajusta si es necesario
6. **Continuar**: Procede normalmente con los siguientes pasos del formulario

## 🎯 Consejos para Mejores Resultados

- **Sé específico**: Incluye el contexto y objetivo principal
- **Usa verbos de acción**: "Realizar", "Implementar", "Capacitar", etc.
- **Incluye el alcance**: Menciona para quién es la actividad
- **Mínimo 10 caracteres**: El nombre debe ser descriptivo

### Ejemplos de Buenos Nombres:

✅ "Taller de actualización pedagógica para docentes de matemáticas"
✅ "Programa de refuerzo académico para estudiantes de bajo rendimiento"
✅ "Campaña de sensibilización sobre cuidado ambiental en la comunidad educativa"
✅ "Implementación de laboratorio de ciencias con equipamiento moderno"

## 💰 Costos Estimados

### OpenAI (GPT-4o-mini)
- **Costo por generación**: ~$0.001 USD
- **500 actividades**: ~$0.50 USD
- **1000 actividades**: ~$1.00 USD
- **Límites (Tier Free)**: 3 requests/minuto, 200/día
- **Límites (Tier 1)**: 500 requests/minuto

### Google Gemini (Gemini Pro)
- **Costo**: **GRATIS** hasta 60 requests/minuto
- **Sin límite diario** en tier gratuito
- **Ideal para uso institucional**
- **Calidad comparable a GPT-4o-mini**

**💡 Recomendación**: Usa Gemini para evitar costos y límites más estrictos.

## 🚀 Ventajas de Cada Proveedor

### OpenAI
✅ Respuestas muy precisas
✅ Mejor comprensión de contexto complejo
❌ Límites estrictos en tier gratuito
❌ Costos por uso

### Gemini
✅ **GRATIS** con límites generosos
✅ 60 requests/minuto (vs 3 de OpenAI free)
✅ Respuestas rápidas y de calidad
✅ Sin costos para uso institucional
❌ Puede requerir más específicidad en prompts

## 🔧 Solución de Problemas

### Error: "Missing API Key"
- Verifica que hayas agregado la API key correcta en el `.env`
- Asegúrate de usar el nombre correcto: `OPENAI_API_KEY` o `GEMINI_API_KEY`
- Ejecuta `php artisan config:clear`

### Error: "Request rate limit has been exceeded" (OpenAI)
**Solución 1**: Cambia a Gemini
```env
IA_PROVIDER=gemini
GEMINI_API_KEY=tu-api-key-de-gemini
```

**Solución 2**: Espera 30-60 segundos entre solicitudes

**Solución 3**: Actualiza tu tier de OpenAI depositando $5

### Error: "Invalid API Key" (Gemini)
- Verifica que tu API key sea válida en [Google AI Studio](https://makersuite.google.com)
- Regenera la clave si es necesario
- Asegúrate de copiar la clave completa

### La IA genera contenido irrelevante
- Sé más específico en el nombre de la actividad
- Incluye el objetivo y el público objetivo
- Ejemplo bueno: "Taller de capacitación en Excel avanzado para personal administrativo"
- Ejemplo malo: "Capacitación"

## 🚀 Funcionalidades Adicionales

### Modo Manual
- Puedes alternar entre "Generar con IA" y "Modo Manual"
- El modo manual te permite ingresar todos los campos manualmente
- Útil si prefieres tener control total sobre el contenido

### Edición de Actividades
- Al editar una actividad existente, el botón de IA no está disponible
- Esto previene sobrescribir datos existentes accidentalmente

## 📊 Modelo Utilizado

**GPT-4o-mini**
- Modelo optimizado y económico de OpenAI
- Excelente balance entre calidad y costo
- Respuestas rápidas (2-3 segundos típicamente)
- Capacidad de entender contexto institucional

## 🔐 Seguridad

- La API Key nunca se expone en el frontend
- Todas las llamadas se hacen desde el backend (Livewire)
- Los datos se procesan de forma segura
- No se almacena información sensible en los logs de OpenAI

## 📞 Soporte

Si tienes problemas con la configuración:

1. Verifica que tu API Key sea válida
2. Revisa los logs de Laravel: `storage/logs/laravel.log`
3. Consulta la documentación oficial de OpenAI: [https://platform.openai.com/docs](https://platform.openai.com/docs)
