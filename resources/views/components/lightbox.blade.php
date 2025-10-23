@props(['photos' => []])

<div x-data="lightboxGallery({{ json_encode($photos) }})" 
     @keydown.window.escape="closeLightbox"
     @keydown.window.arrow-left="prev"
     @keydown.window.arrow-right="next">
    
    <!-- Lightbox Modal -->
    <div x-show="isOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm p-4"
         @click="closeLightbox">
        
        <div class="relative w-full max-w-6xl h-[90vh]" @click.stop>
            
            <!-- Botón Cerrar -->
            <button @click="closeLightbox"
                    class="absolute top-4 right-4 z-50 p-2 bg-white/10 hover:bg-white/20 rounded-full transition-colors"
                    aria-label="Cerrar galería">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Contador de Imágenes -->
            <div class="absolute top-4 left-1/2 transform -translate-x-1/2 z-50 px-4 py-2 bg-black/50 rounded-full text-white text-sm">
                <span x-text="currentIndex + 1"></span> / <span x-text="photos.length"></span>
            </div>

            <!-- Imagen Principal -->
            <div class="flex items-center justify-center h-full">
                <template x-for="(photo, index) in photos" :key="index">
                    <div x-show="currentIndex === index"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="flex items-center justify-center h-full">
                        <img :src="photo.url" 
                             :alt="photo.alt || 'Foto de perfil'"
                             class="max-h-full max-w-full object-contain rounded-lg shadow-2xl">
                    </div>
                </template>
            </div>

            <!-- Botón Anterior -->
            <button @click.stop="prev"
                    x-show="photos.length > 1"
                    class="absolute left-4 top-1/2 -translate-y-1/2 p-3 bg-white/10 hover:bg-white/20 rounded-full transition-colors"
                    aria-label="Imagen anterior">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Botón Siguiente -->
            <button @click.stop="next"
                    x-show="photos.length > 1"
                    class="absolute right-4 top-1/2 -translate-y-1/2 p-3 bg-white/10 hover:bg-white/20 rounded-full transition-colors"
                    aria-label="Imagen siguiente">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Indicadores (Dots) -->
            <div x-show="photos.length > 1"
                 class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
                <template x-for="(photo, index) in photos" :key="index">
                    <button @click="currentIndex = index"
                            class="w-2 h-2 rounded-full transition-all duration-300"
                            :class="index === currentIndex ? 'bg-white w-8' : 'bg-white/50 hover:bg-white/75'"
                            :aria-label="`Ir a imagen ${index + 1}`">
                    </button>
                </template>
            </div>

        </div>
    </div>
</div>

<script>
function lightboxGallery(photos) {
    return {
        photos: photos,
        currentIndex: 0,
        isOpen: false,
        
        openLightbox(index = 0) {
            this.currentIndex = index;
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },
        
        closeLightbox() {
            this.isOpen = false;
            document.body.style.overflow = 'auto';
        },
        
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.photos.length;
        },
        
        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.photos.length) % this.photos.length;
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>
