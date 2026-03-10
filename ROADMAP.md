# 🚀 Roadmap de Lanzamiento: Big-Dad Platform

**Fecha Objetivo de Lanzamiento:** Este Viernes

Este documento centraliza los pasos críticos para asegurar un despliegue exitoso en producción (Railway) y evitar bloqueos de usuarios durante el día 1.

## Fase 1: Infraestructura y Entorno (Día -2)

- [x] **Almacenamiento (Storage):**
- [ ] Verificar si se usará S3/Cloudflare R2 para fotos de perfil (`profiles/{hash}`).
- [ ] Si se usa almacenamiento local en Railway, **asegurar que un Volumen Persistente esté montado** en la ruta de `/storage`.
- [x] **Variables de Entorno (.env) Producción:**
- [x] `APP_ENV=production` y `APP_DEBUG=false`.
- [x] Credenciales de Mercado Pago: `access_token` y `public_key` de producción.
- [ ] **CRÍTICO:** Configurar `services.mercadopago.webhook_secret` para que no fallen las validaciones de firmas de los pagos.
- [ ] Configurar credenciales de Resend/SMTP para el envío de correos.

## Fase 2: Pruebas de Flujos Críticos (Día -1)

- [ ] **Flujo de Registro y Age Gate:**
- [ ] Probar validación `before:-18 years` en el backend.
- [ ] Verificar que el email de confirmación (Resend) llegue a la bandeja de entrada y no a Spam.
- [ ] **Flujo de Pagos (Mercado Pago):**
- [ ] Realizar una compra de prueba en producción (ej. Boost o Super Likes a $1) usando tarjeta real o cuenta de prueba, y hacer un reembolso inmediato (`/refund`).
- [ ] Verificar que el Webhook procese correctamente el evento y otorgue los beneficios en el sistema (`PaymentAuditLog`).
- [ ] **Flujo de Matching:**
- [ ] Crear un perfil _Sugar Daddy_ y un perfil _Sugar Baby_. Hacer match mutuo y verificar creación de sala de chat.

## Fase 3: Preparación Operativa (Día 0 - Horas previas)

- [ ] **Limpieza de BD:** Ejecutar `php artisan migrate:fresh --seed` (SI ES NECESARIO) para limpiar usuarios de prueba, dejando solo cuentas de administradores.
- [ ] **Caché de Optimización:**
- [ ] Ejecutar `php artisan config:cache`
- [ ] Ejecutar `php artisan route:cache`
- [ ] Ejecutar `php artisan view:cache`
- [ ] **SEO y Visibilidad:** Ejecutar `php artisan sitemap:generate` para crear el archivo `sitemap.xml` final y asegurar su acceso en `/sitemap.xml`.

## Fase 4: Lanzamiento y Monitoreo (Día 0)

- [ ] **Apertura de Tráfico:** Anunciar o habilitar el tráfico a la landing page.
- [ ] **Monitor de Moderación Activa:**
- [ ] Mantener abierta la ruta `/admin/moderation/photos` para aprobar las fotos de los primeros usuarios de inmediato y evitar que la plataforma se vea "vacía".
- [ ] Monitorear los logs de Laravel en Railway buscando errores fatales (`HTTP 500`).
- [ ] Vigilar los registros de fallos de Webhooks de Mercado Pago en la tabla `PaymentAuditLog`.
